<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\tblreserve;

class tblorderreceipt extends Model
{
    use HasFactory;
    protected $table = 'tblorderreceipt';
    protected $primaryKey = 'ordDet_ID';
    protected $fillable = [
        'orderitems_id',
        'customer_id',
        'payment_id',
        'reserve_id',
        'delivery_date',
        'order_date',
    ];
    public function customer()
    {
        return $this->belongsTo(tblcustomer::class, 'customer_id', 'customer_id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(tblpaymentmethod::class, 'payment_id', 'payment_id');
    }
    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'product_id', 'product_id');
    }

    public function reservation()
    {
        return $this->belongsTo(tblreserve::class, 'reserve_id', 'reserve_id');
    }

    public function service()
    {
        return $this->belongsTo(tblservice::class, 'service_ID', 'service_ID');
    }
    
}

