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
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // العمود ID
            $table->string('company_name'); // اسم الشركة
            $table->string('name'); // اسم العميل
            $table->string('website')->nullable(); // الموقع (يمكن أن يكون اختياريًا)
            $table->string('logo')->nullable(); // الشعار (اختياري)
            $table->string('phone'); // الهاتف
            $table->decimal('balance', 15, 2)->default(0.00); // الرصيد مع قيمة افتراضية
            $table->datetime('last_login')->nullable(); // آخر تسجيل دخول (اختياري)
            $table->string('address')->nullable(); // العنوان (اختياري)
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

// Company name website logo fool balance lost login at this sitting
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
