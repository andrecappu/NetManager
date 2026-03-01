<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['comune', 'provincia', 'altro']);
            $table->string('codice_istat')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('referente')->nullable();
            $table->string('contatto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enti');
    }
};
