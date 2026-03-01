<?php

namespace App\Modules\Network\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Network\Models\Apparato;
use App\Modules\Network\Requests\StoreApparatoRequest;
use App\Modules\Network\Requests\UpdateApparatoRequest;
use App\Modules\Network\Resources\ApparatoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApparatoController extends Controller
{
    public function index(Request $request)
    {
        $query = Apparato::with('sito.ente');

        if ($request->has('sito_id')) {
            $query->where('sito_id', $request->sito_id);
        }
        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->has('stato')) {
            $query->where('stato', $request->stato);
        }

        return ApparatoResource::collection($query->paginate(15));
    }

    public function store(StoreApparatoRequest $request)
    {
        $apparato = Apparato::create($request->validated());
        return new ApparatoResource($apparato);
    }

    public function show(Apparato $apparato)
    {
        return new ApparatoResource($apparato->load('sito.ente'));
    }

    public function update(UpdateApparatoRequest $request, Apparato $apparato)
    {
        $apparato->update($request->validated());
        return new ApparatoResource($apparato);
    }

    public function destroy(Apparato $apparato)
    {
        $apparato->delete();
        return response()->noContent();
    }

    public function stats(Request $request)
    {
        $stats = [
            'totale' => Apparato::count(),
            'per_stato' => Apparato::select('stato', DB::raw('count(*) as count'))
                ->groupBy('stato')
                ->get(),
            'per_tipo' => Apparato::select('tipo', DB::raw('count(*) as count'))
                ->groupBy('tipo')
                ->get(),
        ];

        return response()->json($stats);
    }
}
