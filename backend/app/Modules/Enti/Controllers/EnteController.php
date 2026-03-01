<?php

namespace App\Modules\Enti\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Enti\Models\Ente;
use App\Modules\Enti\Requests\StoreEnteRequest;
use App\Modules\Enti\Requests\UpdateEnteRequest;
use App\Modules\Enti\Resources\EnteResource;
use Illuminate\Http\Request;

class EnteController extends Controller
{
    public function index(Request $request)
    {
        $query = Ente::query();
        
        // Se l'utente non è admin, vede solo i suoi enti
        if (!$request->user()->hasRole('admin')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('users.id', $request->user()->id);
            });
        }

        return EnteResource::collection($query->paginate(15));
    }

    public function store(StoreEnteRequest $request)
    {
        $ente = Ente::create($request->validated());
        return new EnteResource($ente);
    }

    public function show(Ente $ente)
    {
        return new EnteResource($ente);
    }

    public function update(UpdateEnteRequest $request, Ente $ente)
    {
        $ente->update($request->validated());
        return new EnteResource($ente);
    }

    public function destroy(Ente $ente)
    {
        $ente->delete();
        return response()->noContent();
    }
}
