<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_states', function (Blueprint $table) {
            $table->id();

            $table->foreignId('room_id')
                ->unique()
                ->constrained('rooms')
                ->cascadeOnDelete();

            $table->foreignId('song_id')
                ->nullable()
                ->constrained('songs')
                ->nullOnDelete();

            $table->enum('status', [
                'playing',
                'paused',
                'stopped',
            ])->default('stopped');

            $table->decimal('position', 10, 3)->default(0);

            $table->timestamp('updated_at_server')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_states');
    }
};
