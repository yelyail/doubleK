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
        'ordDet_ID',
        'returnDate',
        'returnReason',
        'return_status',
    ];

    public $timestamp = false;

    public function orderreceipt()
    {
        return $this->belongsTo(tblorderreceipt::class, 'ordDet_ID', 'ordDet_ID');
    }
}
