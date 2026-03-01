<?php

namespace App\Modules\Topology\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Topology\Models\Sito;
use App\Modules\Topology\Models\Collegamento;
use Illuminate\Http\Request;

class TopologyController extends Controller
{
    public function map(Request $request)
    {
        $siti = Sito::with('apparati')->whereNotNull('lat')->whereNotNull('lng')->get();
        $collegamenti = Collegamento::with(['sitoOrigine', 'sitoDestinazione'])->get();

        $features = [];

        foreach ($siti as $sito) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$sito->lng, (float)$sito->lat]
                ],
                'properties' => [
                    'id' => $sito->id,
                    'nome' => $sito->nome,
                    'tipo' => $sito->tipo,
                    'apparati_count' => $sito->apparati->count(),
                ]
            ];
        }

        foreach ($collegamenti as $collegamento) {
            if ($collegamento->sitoOrigine->lat && $collegamento->sitoOrigine->lng &&
                $collegamento->sitoDestinazione->lat && $collegamento->sitoDestinazione->lng) {
                
                $features[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'LineString',
                        'coordinates' => [
                            [(float)$collegamento->sitoOrigine->lng, (float)$collegamento->sitoOrigine->lat],
                            [(float)$collegamento->sitoDestinazione->lng, (float)$collegamento->sitoDestinazione->lat]
                        ]
                    ],
                    'properties' => [
                        'id' => $collegamento->id,
                        'tipo' => $collegamento->tipo,
                        'banda_mbps' => $collegamento->banda_mbps,
                    ]
                ];
            }
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }

    public function graph(Request $request)
    {
        $siti = Sito::all();
        $collegamenti = Collegamento::all();

        $nodes = $siti->map(function ($sito) {
            return [
                'id' => $sito->id,
                'label' => $sito->nome,
                'group' => $sito->tipo,
            ];
        });

        $edges = $collegamenti->map(function ($collegamento) {
            return [
                'id' => $collegamento->id,
                'from' => $collegamento->sito_origine_id,
                'to' => $collegamento->sito_destinazione_id,
                'label' => $collegamento->banda_mbps ? $collegamento->banda_mbps . ' Mbps' : '',
                'type' => $collegamento->tipo,
            ];
        });

        return response()->json([
            'nodes' => $nodes,
            'edges' => $edges,
        ]);
    }
}
