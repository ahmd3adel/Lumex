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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('invoice_id'); // Reference to the invoice
            $table->unsignedBigInteger('product_id'); // Reference to the product
            $table->integer('quantity'); // Quantity of the product
            $table->decimal('unit_price', 15, 2); // Price per unit
            $table->decimal('subtotal', 15, 2); // Total price for the quantity

            $table->timestamps(); // Created and updated timestamps

            // Foreign key constraints
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};
