<?php

namespace App\Modules\Topology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Topology\Models\Collegamento;
use App\Modules\Topology\Requests\StoreCollegamentoRequest;
use App\Modules\Topology\Requests\UpdateCollegamentoRequest;
use App\Modules\Topology\Resources\CollegamentoResource;
use Illuminate\Http\Request;

class CollegamentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Collegamento::with(['sitoOrigine', 'sitoDestinazione']);

        if ($request->has('ente_id')) {
            $enteId = $request->ente_id;
            $query->whereHas('sitoOrigine', function ($q) use ($enteId) {
                $q->where('ente_id', $enteId);
            })->orWhereHas('sitoDestinazione', function ($q) use ($enteId) {
                $q->where('ente_id', $enteId);
            });
        }

        return CollegamentoResource::collection($query->paginate(15));
    }

    public function store(StoreCollegamentoRequest $request)
    {
        $collegamento = Collegamento::create($request->validated());
        return new CollegamentoResource($collegamento);
    }

    public function show(Collegamento $collegamento)
    {
        return new CollegamentoResource($collegamento->load(['sitoOrigine', 'sitoDestinazione']));
    }

    public function update(UpdateCollegamentoRequest $request, Collegamento $collegamento)
    {
        $collegamento->update($request->validated());
        return new CollegamentoResource($collegamento);
    }

    public function destroy(Collegamento $collegamento)
    {
        $collegamento->delete();
        return response()->noContent();
    }
}
