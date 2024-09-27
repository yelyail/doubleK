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
        Schema::create('tblsupplier', function (Blueprint $table) {
            $table->id("supplier_ID");
            $table->unsignedBigInteger('user_ID')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->string('supplier_landline')->nullable();
            $table->string('supplier_address')->nullable();
            $table->string('supplier_email')->nullable();
            $table->boolean('archived')->default(false);
            $table->foreign('user_ID')->references('user_ID')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblsupplier');
    }
};
