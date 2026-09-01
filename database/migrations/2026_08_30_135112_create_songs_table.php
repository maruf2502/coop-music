<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('artist_id')
                ->constrained('artists')
                ->cascadeOnDelete();

            $table->foreignId('album_id')
                ->nullable()
                ->constrained('albums')
                ->nullOnDelete();

            $table->string('youtube_id')->unique();
            $table->string('title');
            $table->text('thumbnail')->nullable();

            $table->unsignedInteger('duration')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
