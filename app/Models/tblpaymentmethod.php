<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblpaymentmethod extends Model
{
    use HasFactory;
    protected $table = 'tblpaymentmethod';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'payment_type',
        'reference_num',
        'payment',
    ];

    public $timestamp = false;

    // A payment method has many order receipts
    public function orderReceipts()
    {
        return $this->hasMany(tblorderreceipt::class, 'payment_id', 'payment_id');
    }
}

