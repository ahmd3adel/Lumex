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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // العمود ID
            $table->string('company_name'); // اسم الشركة
            $table->string('name')->nullable(); // اسم العميل
            $table->string('website')->nullable(); // الموقع (يمكن أن يكون اختياريًا)
            $table->string('phone')->nullable(); // الهاتف
            $table->decimal('balance')->default(0);
            $table->datetime('last_login')->nullable(); // آخر تسجيل دخول (اختياري)
            $table->string('address')->nullable(); // العنوان (اختياري)
            $table->foreignId('store_id')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
