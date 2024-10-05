<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblproduct extends Model
{
    use HasFactory;

    protected $table = 'tblproduct'; // Table name
    protected $primaryKey = 'product_id'; // Primary key
    protected $fillable = [
        'inventory_ID',
        'category_id',
        'product_name',
        'unit_price',
        'updatedQty',
        'product_desc',
        'prod_add',
        'warranty',
        'archived',
    ];

    public $timestamp = false;

    public function category()
    {
        return $this->belongsTo(tblcategory::class, 'category_id', 'category_id');
    }

    public function inventory()
    {
        return $this->hasOne(tblinventory::class, 'inventory_ID', 'inventory_ID'); // Ensure 'inventory_ID' is correct
    }

    public function orderReceipts()
    {
        return $this->hasMany(tblorderreceipt::class, 'product_id', 'product_id');
    }

    // Relationship with returns
    public function returns()
    {
        return $this->hasMany(tblreturn::class, 'product_id', 'product_id');
    }
}
