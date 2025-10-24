<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('search_log_photos', function (Blueprint $table) {
            $table->id('photo_id');
            $table->foreignId('log_id')->constrained('search_logs', 'log_id')->onDelete('cascade');
            $table->string('path');
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedInteger('size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_log_photos');
    }
};