<?php

// database/migrations/xxxx_xx_xx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('service_ID');
            $table->foreignId('payment_id');
            $table->foreignId('user_ID');
            $table->integer('qty_order');
            $table->decimal('total_price', 10, 2);
            $table->date('order_date');
            $table->date('delivery_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
