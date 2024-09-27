<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblservice extends Model
{
    use HasFactory;
    protected $table = 'tblservice';
    protected $primaryKey = 'service_ID';
    protected $fillable = [
        'service_name',
        'description',
        'service_fee',
        'service_status',
    ];

    // A service has many order receipts
    public function orderReceipts()
    {
        return $this->hasMany(tblorderreceipt::class, 'service_ID', 'service_ID');
    }
}

