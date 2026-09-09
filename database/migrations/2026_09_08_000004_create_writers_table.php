<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('writers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->string('genre_tag')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedInteger('followers_count')->default(0);
            $table->decimal('rating_average', 2, 1)->default(0);
            $table->unsignedSmallInteger('joined_year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writers');
    }
};
