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
        Schema::create('tblservice', function (Blueprint $table) {
            $table->id("service_ID");
            $table->string('service_name')->nullable();
            $table->string('description')->nullable();
            $table->float('service_fee')->nullable();
            $table->string('service_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblservice');
    }
};