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
            $table->foreignId('generic_id')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->bigInteger('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('cost')->default(0);
            $table->decimal('price')->default(0);
            $table->string('status')->default('active');
            $table->dateTime('expired_at')->nullable();
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
