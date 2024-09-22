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
    public function service()
    {
        return $this->belongsTo(tblservice::class, 'service_ID', 'service_ID');
    }
    public function customer()
    {
        return $this->belongsTo(tblcustomer::class, 'customer_id', 'customer_id');
    }
    public function payment()
    {
        return $this->belongsTo(tblpaymentmethod::class, 'payment_id', 'payment');
    }
    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'product_id', 'product_id');
    }
    public function supplier()
    {
        return $this->belongsTo(tblsupplier::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }

    
}
