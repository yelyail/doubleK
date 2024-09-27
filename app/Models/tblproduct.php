<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblproduct extends Model
{
    use HasFactory;
    protected $table = 'tblproduct';
    protected $primaryKey = 'product_id';
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
}
