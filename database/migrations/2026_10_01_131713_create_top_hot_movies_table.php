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
        Schema::create('top_hot_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
            $table->unsignedTinyInteger('rank')->default(1);
            $table->string('sub_title')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('badge_text_2')->nullable();
            $table->string('age_rating')->default('T13');
            $table->string('custom_poster')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_hot_movies');
    }
};
