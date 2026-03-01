<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collegamenti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sito_origine_id')->constrained('siti')->cascadeOnDelete();
            $table->foreignId('sito_destinazione_id')->constrained('siti')->cascadeOnDelete();
            $table->enum('tipo', ['fibra', 'wireless', 'rame']);
            $table->unsignedInteger('banda_mbps')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collegamenti');
    }
};
