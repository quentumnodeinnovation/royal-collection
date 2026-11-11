<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow', 120)->nullable();
            $table->string('title', 180);
            $table->string('subtitle', 320)->nullable();
            $table->string('image');
            $table->string('primary_cta_label', 60)->nullable();
            $table->string('primary_cta_url', 240)->nullable();
            $table->string('secondary_cta_label', 60)->nullable();
            $table->string('secondary_cta_url', 240)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
