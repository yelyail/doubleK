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
        'delivery_date',
        'order_date',
        'order_status', 
    ];

    public $timestamps = false;
    public function customer()
    {
        return $this->belongsTo(tblcustomer::class, 'customer_id', 'customer_id');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(tblpaymentmethod::class, 'payment_id', 'payment_id');
    }
    public function orderitems()
    {
        return $this->hasMany(tblorderitems::class, 'orderitems_id', 'orderitems_id');
    }

    public function credit()
    {
        return $this->hasOne(tblcredit::class, 'ordDet_ID', 'ordDet_ID');    }
    
}

