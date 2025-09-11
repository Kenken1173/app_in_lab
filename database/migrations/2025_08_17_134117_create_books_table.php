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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_title');
            $table->string('author');
            $table->date('published_date');
            $table->string('borrower')->nullable(); //値を入れなくても良い
            $table->string('image_path')->nullable(); //値を入れなくても良い
            $table->string('field')->nullable(); //値を入れなくても良い
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
