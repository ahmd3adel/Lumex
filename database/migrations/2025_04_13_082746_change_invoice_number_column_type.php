<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_no')->change();
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            $table->foreign('invoice_id')->references('invoice_no')->on('invoices');
        });
    }

    public function down()
    {
        Schema::table('invoice_details', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoice_no')->change();
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            $table->foreign('invoice_id')->references('invoice_no')->on('invoices');
        });
    }
};
