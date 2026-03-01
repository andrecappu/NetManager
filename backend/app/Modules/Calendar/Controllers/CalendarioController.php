<?php

namespace App\Modules\Calendar\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Calendar\Models\CalendarioEvento;
use App\Modules\Calendar\Requests\StoreCalendarioEventoRequest;
use App\Modules\Calendar\Requests\UpdateCalendarioEventoRequest;
use App\Modules\Calendar\Resources\CalendarioEventoResource;
use App\Modules\Auth\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        $query = CalendarioEvento::with(['user', 'intervento']);

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('mese') && $request->has('anno')) {
            $start = Carbon::create($request->anno, $request->mese, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            
            $query->whereBetween('data_inizio', [$start, $end])
                  ->orWhereBetween('data_fine', [$start, $end]);
        }

        return CalendarioEventoResource::collection($query->get());
    }

    public function store(StoreCalendarioEventoRequest $request)
    {
        $evento = CalendarioEvento::create($request->validated());
        return new CalendarioEventoResource($evento->load(['user', 'intervento']));
    }

    public function show(CalendarioEvento $calendarioEvento)
    {
        return new CalendarioEventoResource($calendarioEvento->load(['user', 'intervento']));
    }

    public function update(UpdateCalendarioEventoRequest $request, CalendarioEvento $calendarioEvento)
    {
        $calendarioEvento->update($request->validated());
        return new CalendarioEventoResource($calendarioEvento->load(['user', 'intervento']));
    }

    public function destroy(CalendarioEvento $calendarioEvento)
    {
        $calendarioEvento->delete();
        return response()->noContent();
    }

    public function disponibilita(Request $request)
    {
        $request->validate([
            'data' => 'required|date'
        ]);

        $data = Carbon::parse($request->data)->startOfDay();
        $endData = $data->copy()->endOfDay();

        $operatori = User::role('operatore')->get();
        
        $disponibilita = $operatori->map(function ($operatore) use ($data, $endData) {
            $eventi = CalendarioEvento::where('user_id', $operatore->id)
                ->where(function ($q) use ($data, $endData) {
                    $q->whereBetween('data_inizio', [$data, $endData])
                      ->orWhereBetween('data_fine', [$data, $endData])
                      ->orWhere(function ($q2) use ($data, $endData) {
                          $q2->where('data_inizio', '<=', $data)
                             ->where('data_fine', '>=', $endData);
                      });
                })->count();

            return [
                'operatore_id' => $operatore->id,
                'nome' => $operatore->nome . ' ' . $operatore->cognome,
                'occupato' => $eventi > 0,
                'eventi_count' => $eventi
            ];
        });

        return response()->json($disponibilita);
    }
}
