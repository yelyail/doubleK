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
        'user_ID',
        'qty_order',
        'total_price',
        'order_date',
        'order_status',
    ];

    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'product_id', 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }

}

