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
        Schema::create('receipt_vouchers', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('voucher_no')->nullable(); // Unique Voucher Number
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // Reference to Client
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade'); // Reference to Client
            $table->decimal('amount', 15, 2); // Amount Received
            $table->string('payment_method')->default('cash')->nullable(); // Payment Type (Cash, Bank, etc.)
            $table->text('notes')->nullable(); // Additional Notes
            $table->date('receipt_date')->nullable(); // Date of Receipt
            $table->unique(['voucher_no' , 'store_id']);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps(); // Created and Updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_vouchers');
    }
};
