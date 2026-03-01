<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    // Auth routes
    Route::post('/login', [\App\Modules\Auth\Controllers\AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [\App\Modules\Auth\Controllers\AuthController::class, 'logout']);
        Route::get('/me', [\App\Modules\Auth\Controllers\AuthController::class, 'me']);
        
        // Enti
        Route::apiResource('enti', \App\Modules\Enti\Controllers\EnteController::class);
        
        // Siti
        Route::apiResource('enti.siti', \App\Modules\Topology\Controllers\SitoController::class)->shallow();
        
        // Apparati
        Route::get('/apparati/stats', [\App\Modules\Network\Controllers\ApparatoController::class, 'stats']);
        Route::apiResource('apparati', \App\Modules\Network\Controllers\ApparatoController::class);
        
        // Topology
        Route::get('/topology/map', [\App\Modules\Topology\Controllers\TopologyController::class, 'map']);
        Route::get('/topology/graph', [\App\Modules\Topology\Controllers\TopologyController::class, 'graph']);
        Route::apiResource('subnets', \App\Modules\Topology\Controllers\SubnetController::class);
        Route::apiResource('collegamenti', \App\Modules\Topology\Controllers\CollegamentoController::class);
        
        // Tasks (Interventi)
        Route::patch('/interventi/{id}/stato', [\App\Modules\Tasks\Controllers\InterventoController::class, 'updateStato']);
        Route::patch('/interventi/{id}/completa', [\App\Modules\Tasks\Controllers\InterventoController::class, 'completa']);
        Route::get('/interventi/{id}/report', [\App\Modules\Tasks\Controllers\InterventoController::class, 'report']);
        Route::post('/interventi/{id}/allegati', [\App\Modules\Tasks\Controllers\InterventoController::class, 'uploadAllegato']);
        Route::apiResource('interventi', \App\Modules\Tasks\Controllers\InterventoController::class);
        
        // Checklist
        Route::patch('/checklist-items/{id}', [\App\Modules\Tasks\Controllers\ChecklistItemController::class, 'toggle']);
        
        // Calendar
        Route::get('/operatori/disponibilita', [\App\Modules\Calendar\Controllers\CalendarioController::class, 'disponibilita']);
        Route::apiResource('calendario', \App\Modules\Calendar\Controllers\CalendarioController::class);
        
        // Notifications
        Route::get('/notifiche', [\App\Modules\Notifications\Controllers\NotificationController::class, 'index']);
        Route::patch('/notifiche/{id}/leggi', [\App\Modules\Notifications\Controllers\NotificationController::class, 'markAsRead']);
    });
});
