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
        Schema::create('tblinventory', function (Blueprint $table) {
            $table->id("inventory_ID");
            $table->unsignedBigInteger('supplier_ID')->nullable();
            $table->integer('stock_qty')->nullable();
            $table->date('lastRestockDate')->nullable();
            $table->date('nextRestockDate')->nullable();
            $table->foreign('supplier_ID')->references('supplier_ID')->on('tblsupplier')->onDelete('cascade')->onUpdate('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblinventory');
    }
};
