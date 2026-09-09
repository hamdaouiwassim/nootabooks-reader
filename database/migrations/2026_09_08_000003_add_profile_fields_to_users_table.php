<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('name');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('location')->nullable()->after('bio');
            $table->unsignedInteger('points')->default(0)->after('location');
            $table->boolean('is_public')->default(true)->after('points');
            $table->boolean('show_reading_activity')->default(true)->after('is_public');
            $table->boolean('allow_messages')->default(false)->after('show_reading_activity');
            $table->boolean('two_factor_enabled')->default(false)->after('allow_messages');
            $table->json('notification_preferences')->nullable()->after('two_factor_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'bio',
                'location',
                'points',
                'is_public',
                'show_reading_activity',
                'allow_messages',
                'two_factor_enabled',
                'notification_preferences',
            ]);
        });
    }
};
