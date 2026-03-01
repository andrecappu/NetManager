<?php

namespace App\Modules\Tasks\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tasks\Models\ChecklistItem;
use App\Modules\Tasks\Resources\ChecklistItemResource;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function toggle(Request $request, ChecklistItem $checklistItem)
    {
        $completato = !$checklistItem->completato;
        
        $checklistItem->update([
            'completato' => $completato,
            'completato_at' => $completato ? now() : null,
            'completato_da' => $completato ? $request->user()->id : null,
        ]);

        return new ChecklistItemResource($checklistItem->load('completatoDa'));
    }
}
