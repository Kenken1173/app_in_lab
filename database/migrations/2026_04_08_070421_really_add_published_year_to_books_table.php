<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->integer('published_year')->nullable()->after('author');
        });

        DB::statement("
            UPDATE books
            SET published_year = CAST(strftime('%Y', published_date) AS INTEGER)
            WHERE published_date IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('published_year');
        });
    }
};