<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apparati', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sito_id')->constrained('siti')->cascadeOnDelete();
            $table->enum('tipo', ['switch', 'router', 'ap', 'telecamera', 'nvr', 'fibra', 'altro']);
            $table->string('marca')->nullable();
            $table->string('modello')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('subnet')->nullable();
            $table->string('seriale')->nullable();
            $table->enum('stato', ['attivo', 'guasto', 'manutenzione'])->default('attivo');
            $table->date('data_installazione')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apparati');
    }
};
