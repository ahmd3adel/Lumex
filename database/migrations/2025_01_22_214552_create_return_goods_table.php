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
        Schema::create('return_goods', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('return_no'); // Unique invoice number
            $table->foreignId('client_id'); // Reference to the client
            $table->foreignId('store_id')->nullable(); // Reference to the store
            $table->decimal('total', 15, 2); // Total amount before discount
            $table->decimal('discount', 15, 2)->default(0); // Discount amount
            $table->decimal('net_total', 15, 2); // Total after discount
            $table->text('notes')->nullable(); // Total after discount
            $table->date('return_date'); // Date of the invoice
            $table->unsignedInteger('pieces_no')->default(0); // Date of the invoice
            $table->unique(['return_no' , 'store_id']);
            $table->timestamps(); // Created and updated timestamps
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();
            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_goods');
    }
};
