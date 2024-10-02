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
        Schema::create('tblreturn', function (Blueprint $table) {
            $table->id('return_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->date('returnDate');
            $table->string('returnReason')->nullable();
            $table->string('return_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblreturn');
    }
};