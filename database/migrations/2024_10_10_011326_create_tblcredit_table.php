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
        Schema::create('tblcredit', function (Blueprint $table) {
            $table->id('creditID');
            $table->unsignedBigInteger('ordDet_ID')->nullable();
            $table->string('credit_type');
            $table->string('credit_status')->nullable();
            $table->foreign('ordDet_ID')->references('ordDet_ID')->on('tblorderreceipt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblcredit');
    }
};
