<?php

namespace App\Modules\Tasks\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tasks\Models\Intervento;
use App\Modules\Tasks\Models\Allegato;
use App\Modules\Tasks\Requests\StoreInterventoRequest;
use App\Modules\Tasks\Requests\UpdateInterventoRequest;
use App\Modules\Tasks\Resources\InterventoResource;
use App\Modules\Notifications\Notifications\NuovoInterventoNotification;
use App\Modules\Reports\Jobs\GeneraReportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InterventoController extends Controller
{
    public function index(Request $request)
    {
        $query = Intervento::with(['ente', 'sito', 'apparato', 'assegnatoA', 'creatoDa']);

        if ($request->has('board') && $request->board == 'true') {
            // Raggruppa per stato per la Kanban board
            $interventi = $query->get();
            return response()->json([
                'todo' => InterventoResource::collection($interventi->where('stato', 'todo')),
                'in_corso' => InterventoResource::collection($interventi->where('stato', 'in_corso')),
                'completato' => InterventoResource::collection($interventi->where('stato', 'completato')),
                'annullato' => InterventoResource::collection($interventi->where('stato', 'annullato')),
            ]);
        }

        if ($request->has('ente_id')) {
            $query->where('ente_id', $request->ente_id);
        }
        if ($request->has('assegnato_a')) {
            $query->where('assegnato_a', $request->assegnato_a);
        }
        if ($request->has('priorita')) {
            $query->where('priorita', $request->priorita);
        }

        return InterventoResource::collection($query->paginate(15));
    }

    public function store(StoreInterventoRequest $request)
    {
        $data = $request->validated();
        $data['creato_da'] = $request->user()->id;
        
        $intervento = Intervento::create($data);

        if ($intervento->assegnato_a) {
            $assegnatoA = $intervento->assegnatoA;
            if ($assegnatoA) {
                // Dispatch notification
                $assegnatoA->notify(new NuovoInterventoNotification($intervento));
            }
        }

        return new InterventoResource($intervento->load(['ente', 'sito', 'apparato', 'assegnatoA', 'creatoDa']));
    }

    public function show(Intervento $intervento)
    {
        return new InterventoResource($intervento->load([
            'ente', 'sito', 'apparato', 'assegnatoA', 'creatoDa', 'checklistItems', 'allegati'
        ]));
    }

    public function update(UpdateInterventoRequest $request, Intervento $intervento)
    {
        $intervento->update($request->validated());
        return new InterventoResource($intervento->load(['ente', 'sito', 'apparato', 'assegnatoA', 'creatoDa']));
    }

    public function updateStato(Request $request, Intervento $intervento)
    {
        $request->validate([
            'stato' => 'required|in:todo,in_corso,completato,annullato'
        ]);

        $intervento->update(['stato' => $request->stato]);
        
        return new InterventoResource($intervento);
    }

    public function destroy(Intervento $intervento)
    {
        $intervento->delete();
        return response()->noContent();
    }

    public function completa(Request $request, Intervento $intervento)
    {
        $intervento->update(['stato' => 'completato']);
        
        // Dispatch job to generate PDF report
        GeneraReportJob::dispatch($intervento);

        return response()->json(['message' => 'Intervento completato. Generazione report in corso.']);
    }

    public function uploadAllegato(Request $request, Intervento $intervento)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store('interventi/' . $intervento->id, 'minio');

        $allegato = Allegato::create([
            'intervento_id' => $intervento->id,
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'path_storage' => $path,
            'disk' => 'minio',
            'uploaded_by' => $request->user()->id,
        ]);

        return response()->json($allegato, 201);
    }

    public function report(Intervento $intervento)
    {
        // This will be handled by the ReportService, for now just return a placeholder or the actual file if it exists
        // Assuming the job saves it to minio
        $pdfPath = 'reports/intervento_' . $intervento->id . '.pdf';
        
        if (Storage::disk('minio')->exists($pdfPath)) {
            return Storage::disk('minio')->download($pdfPath);
        }

        return response()->json(['message' => 'Report non ancora generato'], 404);
    }
}
