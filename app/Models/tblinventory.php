<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinventory extends Model
{
    use HasFactory;
    protected $table = 'tblinventory';
    protected $primaryKey = 'inventory_id';
    protected $fillable = [
        'supplier_ID',
        'stock_qty',
        'lastRestockDate',
        'nextRestockDate'
    ];
    public function supplier()
    {
        return $this->belongsTo(tblsupplier::class, 'supplier_ID', 'supplier_ID');
    }
    public function product()
    {
        return $this->hasMany(tblproduct::class, 'product_id', 'product_id');
    }
}
