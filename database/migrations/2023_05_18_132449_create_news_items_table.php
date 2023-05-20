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
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('origin');
            $table->string('origin_id');
            $table->string('source_slug');
            $table->string('title');
            $table->text('description');
            $table->text('content');
            $table->string('author');
            $table->text('url');
            $table->text('image_url');
            $table->dateTimeTz('published_at')->nullable();
            $table->timestamps();

            $table->foreign('source_slug')->references('slug')->on('news_sources');

            $table->unique(['origin','origin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
