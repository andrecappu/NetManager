<?php

namespace App\Modules\Reports\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Tasks\Models\Intervento;
use App\Modules\Reports\Services\ReportService;

class GeneraReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $intervento;

    public function __construct(Intervento $intervento)
    {
        $this->intervento = $intervento;
    }

    public function handle(ReportService $reportService): void
    {
        $reportService->generateReport($this->intervento);
    }
}
