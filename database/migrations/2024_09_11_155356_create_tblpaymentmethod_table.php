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
        Schema::create('tblpaymentmethod', function (Blueprint $table) {
            $table->id("payment_id");
            $table->string('payment_type')->nullable();
            $table->integer('reference_num')->nullable();
            $table->double('payment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblpaymentmethod');
    }
};
