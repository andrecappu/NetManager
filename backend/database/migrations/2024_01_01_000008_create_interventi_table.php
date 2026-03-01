<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interventi', function (Blueprint $table) {
            $table->id();
            $table->string('titolo');
            $table->text('descrizione')->nullable();
            $table->foreignId('ente_id')->constrained('enti')->cascadeOnDelete();
            $table->foreignId('sito_id')->nullable()->constrained('siti')->nullOnDelete();
            $table->foreignId('apparato_id')->nullable()->constrained('apparati')->nullOnDelete();
            $table->enum('stato', ['todo', 'in_corso', 'completato', 'annullato'])->default('todo');
            $table->enum('priorita', ['bassa', 'media', 'alta', 'urgente'])->default('media');
            $table->foreignId('assegnato_a')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('creato_da')->constrained('users');
            $table->dateTime('data_scadenza')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventi');
    }
};
