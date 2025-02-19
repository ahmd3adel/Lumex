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
            $table->id(); // Primary key
            $table->string('name'); // Product name
            $table->string('cutter_name')->nullable(); // Product name
            $table->text('description')->nullable(); // Product description
            $table->decimal('price', 10, 0); // Product price
            $table->integer('quantity')->default(0); // Quantity in stock
            $table->string('image')->nullable(); // image
            $table->enum('status' , ['active' , 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();

            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();

            $table->foreignId('store_id'); // Quantity in stock
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->timestamps(); // Created at and Updated at timestamps
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
