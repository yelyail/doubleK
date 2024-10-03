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
        Schema::create('tblorderreceipt', function (Blueprint $table) {
            $table->id("ordDet_ID");
            $table->unsignedBigInteger('orderitems_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('payment_id');
            $table->integer('qty_order')->nullable();
            $table->date('delivery_date');
            $table->date('order_date');
            $table->foreign('orderitems_id')->references('orderitems_id')->on('tblorderitems')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('tblcustomer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_id')->references('payment_id')->on('tblpaymentmethod')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblorderreceipt');
    }
};