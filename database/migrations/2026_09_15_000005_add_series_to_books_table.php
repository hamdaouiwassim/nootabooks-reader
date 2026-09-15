<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lets a book be linked to others as part of a series ("الجزء 1",
     * "الجزء 2", ...) — both nullable since most books aren't part of one.
     * series_id nullOnDelete rather than cascade: deleting a series should
     * just un-link its books, not delete them.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('series_id')->nullable()->after('category_id')->constrained('series')->nullOnDelete();
            $table->unsignedInteger('series_order')->nullable()->after('series_id');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('series_id');
            $table->dropColumn('series_order');
        });
    }
};
