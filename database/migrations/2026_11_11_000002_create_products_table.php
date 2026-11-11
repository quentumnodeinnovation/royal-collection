<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('audience', ['men', 'kids'])->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('subtitle');
            $table->string('fit_badge')->nullable();
            $table->string('fabric_badge')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('mrp');
            $table->string('image');
            $table->string('color_name')->default('Obsidian');
            $table->string('color_hex')->default('#111111');
            $table->json('sizes');
            $table->text('description');
            $table->string('fabric_details')->nullable();
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('stock')->default(25);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
