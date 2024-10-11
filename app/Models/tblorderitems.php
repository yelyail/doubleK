<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblorderitems extends Model
{
    use HasFactory;
    protected $table = 'tblorderitems';
    protected $primaryKey = 'orderitems_id';
    protected $fillable = [
        'product_id',
        'service_ID',
        'qty_order',
        'total_price',
    ];

    public $timestamp = false;

    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'product_id', 'product_id');
    }
    public function service()
    {
        return $this->belongsTo(tblservice::class, 'service_ID', 'service_ID');
    }

    public function getCombinedNameAttribute()
    {
        $product_name = $this->product ? $this->product->product_name : null;
        $service_name = $this->service ? $this->service->service_name : null;

        return trim($product_name . ($product_name && $service_name ? ' / ' : '') . $service_name);
    }
    public function orderreceipt() {
        return $this->belongsTo(tblorderreceipt::class, 'orderitems_id', 'orderitems_id');
    }
    
    
}
