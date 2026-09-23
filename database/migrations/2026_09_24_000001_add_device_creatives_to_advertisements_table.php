<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->string('creative_path_tablet')->nullable()->after('creative_path');
            $table->string('creative_path_mobile')->nullable()->after('creative_path_tablet');
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['creative_path_tablet', 'creative_path_mobile']);
        });
    }
};
