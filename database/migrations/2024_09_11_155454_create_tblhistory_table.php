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
        Schema::create('tblhistory', function (Blueprint $table) {
            $table->id('history_id');
            $table->unsignedBigInteger('user_ID')->nullable();
            $table->unsignedBigInteger('ordDet_ID')->nullable();
            $table->integer('qtySold')->nullable();
            $table->float('total_sale')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('status')->nullable();
            $table->foreign('user_ID')->references('user_ID')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ordDet_ID')->references('ordDet_ID')->on('tblorderdetails')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblhistory');
    }
};
