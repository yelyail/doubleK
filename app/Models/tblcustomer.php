<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcustomer extends Model
{
    use HasFactory;
    protected $table = 'tblcustomer';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_name',
        'reservation_date',
        'address',
    ];
}
