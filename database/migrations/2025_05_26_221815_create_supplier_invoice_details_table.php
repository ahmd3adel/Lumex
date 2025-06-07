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
Schema::create('supplier_invoice_details', function (Blueprint $table) {
    $table->id();

    // علاقة بجدول الفواتير الرئيسية
    $table->foreignId('supplier_invoice_id')
          ->constrained('supplier_invoices')
          ->onDelete('cascade');

    // علاقة بالمنتج من جدول supplier_products
    $table->foreignId('supplier_product_id')
          ->constrained('supplier_products')
          ->onDelete('cascade');

    // نوع الوحدة (مثلاً كيلو، متر، قطعة...)
    $table->enum('unit_type', ['meter', 'kilo', 'piece', 'chain', 'custom']);

    // الكمية والسعر الإجمالي
    $table->decimal('quantity', 15, 3);
    $table->decimal('unit_price', 15, 2);
    $table->decimal('total_price', 15, 2);

    // وصف أو ملاحظات إضافية
    $table->string('description')->nullable();

    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoice_details');
    }
};
