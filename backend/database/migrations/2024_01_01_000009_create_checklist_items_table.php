<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervento_id')->constrained('interventi')->cascadeOnDelete();
            $table->string('descrizione');
            $table->boolean('completato')->default(false);
            $table->dateTime('completato_at')->nullable();
            $table->foreignId('completato_da')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};
