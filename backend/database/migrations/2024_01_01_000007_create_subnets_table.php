<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subnets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sito_id')->constrained('siti')->cascadeOnDelete();
            $table->string('cidr');
            $table->string('gateway')->nullable();
            $table->unsignedSmallInteger('vlan_id')->nullable();
            $table->string('descrizione')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subnets');
    }
};
