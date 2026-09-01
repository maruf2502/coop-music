<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_queue', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignId('song_id')
                ->constrained('songs')
                ->cascadeOnDelete();

            $table->foreignId('added_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('position');

            $table->boolean('is_played')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_queue');
    }
};
