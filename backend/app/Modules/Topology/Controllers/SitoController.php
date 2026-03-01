<?php

namespace App\Modules\Topology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Topology\Models\Sito;
use App\Modules\Enti\Models\Ente;
use App\Modules\Topology\Requests\StoreSitoRequest;
use App\Modules\Topology\Requests\UpdateSitoRequest;
use App\Modules\Topology\Resources\SitoResource;
use Illuminate\Http\Request;

class SitoController extends Controller
{
    public function index(Request $request, Ente $ente)
    {
        $query = $ente->siti()->with('apparati');

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        return SitoResource::collection($query->paginate(15));
    }

    public function store(StoreSitoRequest $request, Ente $ente)
    {
        $sito = $ente->siti()->create($request->validated());
        return new SitoResource($sito);
    }

    public function show(Sito $sito)
    {
        return new SitoResource($sito->load(['ente', 'apparati']));
    }

    public function update(UpdateSitoRequest $request, Sito $sito)
    {
        $sito->update($request->validated());
        return new SitoResource($sito);
    }

    public function destroy(Sito $sito)
    {
        $sito->delete();
        return response()->noContent();
    }
}
