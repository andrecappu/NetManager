<?php

namespace App\Modules\Reports\Services;

use App\Modules\Tasks\Models\Intervento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function generateReport(Intervento $intervento)
    {
        $intervento->load(['ente', 'sito', 'apparato', 'assegnatoA', 'creatoDa', 'checklistItems', 'allegati']);

        $pdf = Pdf::loadView('reports.intervento', ['intervento' => $intervento]);
        
        $filename = 'reports/intervento_' . $intervento->id . '.pdf';
        
        Storage::disk('minio')->put($filename, $pdf->output());

        return $filename;
    }
}
