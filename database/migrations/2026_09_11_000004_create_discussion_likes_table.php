<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussion_likes', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discussion_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['user_id', 'discussion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussion_likes');
    }
};
