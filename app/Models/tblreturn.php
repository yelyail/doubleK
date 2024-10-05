<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblreturn extends Model
{
    use HasFactory;
    protected $table = 'tblreturn';
    protected $primaryKey = 'return_id';
    protected $fillable = [
        'product_id',
        'returnDate',
        'returnReason',
        'return_status',
    ];

    public $timestamp = false;

    // A return belongs to a product
    public function product()
    {
        return $this->belongsTo(tblproduct::class, 'product_id', 'product_id');
    }
}
