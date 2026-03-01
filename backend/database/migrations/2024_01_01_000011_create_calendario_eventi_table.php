<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendario_eventi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervento_id')->nullable()->constrained('interventi')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('titolo');
            $table->dateTime('data_inizio');
            $table->dateTime('data_fine');
            $table->string('colore')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendario_eventi');
    }
};
