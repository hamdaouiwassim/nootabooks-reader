<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A book can now belong to more than one category. `books.category_id`
     * stays as the required "primary" category (used for breadcrumbs, SEO,
     * the admin table column, etc.), while this pivot holds the *full* set
     * of categories a book is tagged with — including the primary one, so
     * `Category::books()` (now belongsToMany) sees every associated book
     * regardless of which category is primary.
     */
    public function up(): void
    {
        Schema::create('book_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['book_id', 'category_id']);
        });

        // Backfill: every existing book's primary category becomes its first
        // (and, until an admin adds more, only) pivot row.
        $now = now();
        DB::table('books')->select('id', 'category_id')->orderBy('id')->chunkById(500, function ($books) use ($now) {
            $rows = $books->map(fn ($book) => [
                'book_id' => $book->id,
                'category_id' => $book->category_id,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

            DB::table('book_category')->insert($rows);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_category');
    }
};
