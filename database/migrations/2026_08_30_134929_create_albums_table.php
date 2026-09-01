<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();

            $table->foreignId('artist_id')
                ->constrained('artists')
                ->cascadeOnDelete();

            $table->string('youtube_id')->unique();
            $table->string('title');
            $table->text('thumbnail')->nullable();
            $table->string('release_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
