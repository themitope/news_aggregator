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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('source');
            $table->string('category')->nullable();
            $table->string('author')->nullable();
            $table->dateTime('published_at');
            $table->string('url')->unique();
            $table->json('images')->nullable();
            $table->timestamps();

            $table->index('published_at');
            $table->index('category');
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
