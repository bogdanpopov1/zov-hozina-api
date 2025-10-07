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
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories', 'category_id');
            $table->foreignId('breed_id')->nullable()->constrained('breeds', 'breed_id');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->enum('size', ['small', 'medium', 'large'])->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_vaccinated')->default(false);
            $table->boolean('is_sterilized')->default(false);
            $table->boolean('has_pedigree')->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('price_type', ['free', 'fixed', 'negotiable'])->default('free');
            $table->text('additional_info')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('favorites_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->json('contact_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['breed_id']);
            $table->dropColumn([
                'category_id', 'breed_id', 'age', 'gender', 'size', 'color',
                'is_vaccinated', 'is_sterilized', 'has_pedigree', 'price',
                'price_type', 'additional_info', 'views_count', 'favorites_count',
                'expires_at', 'is_featured', 'contact_info'
            ]);
        });
    }
};
