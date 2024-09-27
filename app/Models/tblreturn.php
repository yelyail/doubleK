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
}
