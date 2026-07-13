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
        Schema::create('media_asset_product', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('media_asset_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('collection');

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['product_id', 'media_asset_id', 'collection']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_asset_product');
    }
};
