<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblinventory extends Model
{
    use HasFactory;
    protected $table = 'tblinventory';
    protected $primaryKey = 'inventory_ID';
    protected $fillable = [
        'inventory_ID',
        'supplier_ID',
        'stock_qty',
        'nextRestockDate',
    ];
}
