<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblorderreceipt extends Model
{
    use HasFactory;
    protected $table = 'tblorderreceipt';
    protected $primaryKey = 'ordDet_ID';
    protected $fillable = [
        'service_ID',
        'customer_id',
        'payment_id',
        'product_id',
        'reserve_id',
        'qty_order',
        'total_price',
        'delivery_date',
        'order_date',
    ];
}
