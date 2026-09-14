<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Every book on the platform is a PDF — the "formats" multi-select
 * (PDF/EPUB/MOBI) was declarative metadata that never matched what's
 * actually uploaded and offered no real value. Removed entirely.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('formats');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->json('formats')->nullable()->after('file_size_mb');
        });
    }
};
