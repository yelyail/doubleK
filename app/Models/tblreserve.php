<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblreserve extends Model
{
    use HasFactory;
    protected $table = 'tblreserve';    
    protected $primaryKey = 'reserve_id';
    protected $fillable = [
        'reserve_id',
        'reserveName',
        'initialPayment',
        'reserveDate',
        'reserve_date',
        'reserveStatus',
        'reserveAction',
    ];

}
