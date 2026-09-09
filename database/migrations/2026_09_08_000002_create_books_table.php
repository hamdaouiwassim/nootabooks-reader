<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author_name');
            $table->text('description_short')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->unsignedInteger('pages_count')->nullable();
            $table->string('language')->default('العربية');
            $table->unsignedSmallInteger('published_year')->nullable();
            $table->decimal('file_size_mb', 8, 2)->nullable();
            $table->json('formats')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedInteger('downloads_count')->default(0);
            $table->decimal('rating_average', 2, 1)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
