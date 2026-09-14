<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('writers', function (Blueprint $table) {
            $table->string('facebook_url')->nullable()->after('bio');
            $table->string('twitter_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('twitter_url');
        });
    }

    public function down(): void
    {
        Schema::table('writers', function (Blueprint $table) {
            $table->dropColumn(['facebook_url', 'twitter_url', 'instagram_url']);
        });
    }
};
