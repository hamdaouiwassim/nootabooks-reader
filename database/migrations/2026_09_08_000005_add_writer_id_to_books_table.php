<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('writer_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            $table->dropColumn('author_name');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('writer_id');
            $table->string('author_name')->after('category_id');
        });
    }
};
