<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcredit extends Model
{
    use HasFactory;
    protected $table = 'tblcredit';
    protected $primaryKey = 'creditID';
    public $timestamps = false;
    protected $fillable = [
        'ordDet_ID',
        'credit_type',
        'credit_status',
        'credit_amount'
    ];
    public function orderReceipt() {
        return $this->belongsTo(tblorderreceipt::class, 'ordDet_ID', 'ordDet_ID');
    }
    
}
