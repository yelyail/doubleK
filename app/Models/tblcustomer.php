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
        'transaction_date',
        'address',
    ];

    public function orderReceipts()
    {
        return $this->hasMany(tblorderreceipt::class, 'customer_id', 'customer_id');
    }
}

