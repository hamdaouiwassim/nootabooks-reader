<?php

use App\Services\Search\ArabicTextNormalizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('search_title')->nullable()->after('title');
            $table->index('search_title');
        });

        // Backfill already-existing rows. Goes straight through the query
        // builder (not Book::save()) so it doesn't touch updated_at or fire
        // unrelated model events — new/edited rows get search_title kept in
        // sync automatically via Book's own `saving` hook from here on.
        DB::table('books')->select('id', 'title')->orderBy('id')->chunkById(200, function ($books) {
            foreach ($books as $book) {
                DB::table('books')->where('id', $book->id)->update([
                    'search_title' => ArabicTextNormalizer::normalize($book->title),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['search_title']);
            $table->dropColumn('search_title');
        });
    }
};
