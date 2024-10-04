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
    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'product_id', 'product_id');
    }
    public function service()
    {
        return $this->belongsTo(tblservice::class, 'service_ID', 'service_ID');
    }
}
