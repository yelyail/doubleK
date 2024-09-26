<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblorderdetails extends Model
{
    use HasFactory;
    protected $table = 'tblorderdetails';
    protected $primaryKey = 'ordDet_ID';
    protected $fillable = [
        'service_ID',
        'customer_id',
        'payment_id',
        'product_id',
        'qty_order',
        'total_price',
        'order_date',
        'delivery_date',
        'order_status',
    ];

    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'product_id', 'product_id');
    }
    public function customer()
    {
        return $this->belongsTo(tblcustomer::class, 'customer_id', 'customer_id');
    }
}

