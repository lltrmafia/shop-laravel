<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->decimal('price');
            $table->decimal('old_price')->nullable();
            $table->unsignedBigInteger('qty');
            $table->integer('parent_id')->nullable();
            $table->foreignId('product_group_id')->constrained('product_groups');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('slug')->unique();
            $table->string('article')->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
