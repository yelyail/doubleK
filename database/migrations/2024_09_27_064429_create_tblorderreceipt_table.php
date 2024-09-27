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
            $table->unsignedBigInteger('service_ID');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger(column: 'reserve_id');
            $table->integer('qty_order')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->date('delivery_date');
            $table->date('order_date');
            $table->foreign('service_ID')->references('service_ID')->on('tblservice')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('tblcustomer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_id')->references('payment_id')->on('tblpaymentmethod')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('product_id')->on('tblproduct')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('reserve_id')->references('reserve_id')->on('tblreserve')->onDelete('cascade')->onUpdate('cascade');
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
