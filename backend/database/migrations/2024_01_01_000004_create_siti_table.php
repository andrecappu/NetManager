<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('ente_id')->constrained('enti')->cascadeOnDelete();
            $table->string('indirizzo')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->enum('tipo', ['rack', 'armadio', 'edificio', 'impianto_vsrv']);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siti');
    }
};
