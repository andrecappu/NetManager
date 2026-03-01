<?php

namespace App\Modules\Topology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Topology\Models\Subnet;
use App\Modules\Topology\Requests\StoreSubnetRequest;
use App\Modules\Topology\Requests\UpdateSubnetRequest;
use App\Modules\Topology\Resources\SubnetResource;
use Illuminate\Http\Request;

class SubnetController extends Controller
{
    public function index(Request $request)
    {
        $query = Subnet::with('sito');

        if ($request->has('sito_id')) {
            $query->where('sito_id', $request->sito_id);
        }

        return SubnetResource::collection($query->paginate(15));
    }

    public function store(StoreSubnetRequest $request)
    {
        $subnet = Subnet::create($request->validated());
        return new SubnetResource($subnet);
    }

    public function show(Subnet $subnet)
    {
        return new SubnetResource($subnet->load('sito'));
    }

    public function update(UpdateSubnetRequest $request, Subnet $subnet)
    {
        $subnet->update($request->validated());
        return new SubnetResource($subnet);
    }

    public function destroy(Subnet $subnet)
    {
        $subnet->delete();
        return response()->noContent();
    }
}
