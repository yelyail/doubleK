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
        Schema::create('tblproduct', function (Blueprint $table) {
            $table->id("product_id");
            $table->unsignedBigInteger('inventory_ID')->nullable();
            $table->string('category_name')->nullable();
            $table->string('product_name')->nullable();
            $table->float('unit_price')->nullable();
            $table->integer('updatedQty')->nullable();
            $table->string('product_desc')->nullable();
            $table->date('prod_add')->nullable();
            $table->integer('warranty')->nullable();
            $table->integer('archived')->nullable();
            $table->foreign('inventory_ID')->references('inventory_ID')->on('tblinventory')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblproduct');
    }
};
