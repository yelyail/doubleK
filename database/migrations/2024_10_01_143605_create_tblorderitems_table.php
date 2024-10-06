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
        Schema::create('tblorderitems', function (Blueprint $table) {
            $table->id('orderitems_id');
            $table->unsignedBigInteger('product_id')->nullable(); 
            $table->unsignedBigInteger('service_ID')->nullable();
            $table->integer('qty_order')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->foreign('product_id')->references('product_id')->on('tblproduct')->onDelete('set null'); // Set to null on delete
            $table->foreign('service_ID')->references('service_ID')->on('tblservice')->onDelete('set null'); // Set to null on delete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblorderitems');
    }
};
