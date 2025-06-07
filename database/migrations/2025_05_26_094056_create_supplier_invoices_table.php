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
        Schema::create('supplier_invoices', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('invoice_no')->nullable(); // Unique invoice number
            $table->foreignId('supplier_id'); // Reference to the client
            $table->foreignId('store_id'); // Reference to the store
            $table->decimal('total'); // Total amount before discount
            $table->decimal('net_total'); // Total amount before discount
            $table->decimal('discount'); // Total amount before discount
            $table->text('notes')->nullable(); // Total after discount
            $table->date('invoice_date'); // Date of the invoice
            $table->unsignedInteger('pieces_no')->default(0)->nullable(); // Date of the invoice
            $table->timestamps(); // Created and updated timestamps
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            // $table->unique(['invoice_no' , 'store_id']);
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();
            // Foreign key constraints
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoices');
    }
};
