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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('url')->unique();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('previous_id')->nullable()->constrained('videos');
            $table->foreignId('next_id')->nullable()->constrained('videos');
            //$table->foreignId('series_id')->nullable()->constrained('series'); # Al no existir la taula séries, mostra error al fer els seeders.
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
