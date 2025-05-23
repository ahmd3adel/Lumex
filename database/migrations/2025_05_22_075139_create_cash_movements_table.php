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
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            // نوع الحركة: داخل أو خارج
            $table->enum('type', ['in', 'out']);
            // المبلغ
            $table->decimal('amount', 10, 2);
            // وصف الحركة
            $table->string('description')->nullable();
            // تاريخ الحركة
            $table->date('movement_date');
            // المستخدم الذي أنشأ الحركة (اختياري)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // المتجر أو المخزن (اختياري)
            $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};
