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
        Schema::create('user', function (Blueprint $table) {
            $table->id("user_ID");
            $table->string('fullname')->nullable();
            $table->string('username')->nullable();
            $table->tinyInteger("jobtitle")->default(0);            $table->string('user_contact')->nullable();
            $table->boolean('archived')->default(false);
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
