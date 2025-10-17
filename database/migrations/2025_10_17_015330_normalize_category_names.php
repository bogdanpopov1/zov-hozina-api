<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('categories')->where('slug', 'dogs')->update(['name' => 'Собака']);
        DB::table('categories')->where('slug', 'cats')->update(['name' => 'Кошка']);
        DB::table('categories')->where('slug', 'birds')->update(['name' => 'Птица']);
    }

    public function down(): void
    {
        DB::table('categories')->where('slug', 'dogs')->update(['name' => 'Собаки']);
        DB::table('categories')->where('slug', 'cats')->update(['name' => 'Кошки']);
    }
};