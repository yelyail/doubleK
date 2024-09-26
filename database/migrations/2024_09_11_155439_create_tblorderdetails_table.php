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
        Schema::create('tblorderdetails', function (Blueprint $table) {
            $table->id("ordDet_ID");
            $table->unsignedBigInteger('service_ID');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty_order')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->date('delivery_date')->nullable();
            $table->date('order_date');
            $table->enum('order_status', ['pending', 'completed', 'cancelled']);
            $table->foreign('service_ID')->references('service_ID')->on('tblservice')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('tblcustomer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('payment_id')->references('payment_id')->on('tblpaymentmethod')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('product_id')->on('tblproduct')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_ID')->references('user_ID')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblorderdetails');
    }
};
