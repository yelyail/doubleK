<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpaymentmethod extends Model
{
    use HasFactory;
    protected $table = 'tblpaymentmethod';
    protected $primaryKey = 'payment';
    protected $fillable = [
        'payment_type'
    ];
}
