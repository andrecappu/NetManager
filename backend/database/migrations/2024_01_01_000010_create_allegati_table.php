<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allegati', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intervento_id')->constrained('interventi')->cascadeOnDelete();
            $table->string('filename');
            $table->string('mime_type');
            $table->string('path_storage');
            $table->string('disk')->default('minio');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allegati');
    }
};
