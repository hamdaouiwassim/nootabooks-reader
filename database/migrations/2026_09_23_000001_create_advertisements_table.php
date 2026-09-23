<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('zone');
            $table->string('creative_path');
            $table->string('target_url');
            $table->string('heading')->nullable();
            $table->text('body_text')->nullable();
            $table->string('alt_text');
            $table->boolean('is_active')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('clicks_count')->default(0);
            $table->timestamps();

            $table->index(['zone', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
