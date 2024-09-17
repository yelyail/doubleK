<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblhistory extends Model
{
    use HasFactory;
    protected $table = 'tblhistory';
    protected $primaryKey = 'history_id';
    protected $fillable = [
        'user_ID',
        'ordDet_ID',
        'qtySold',
        'total_sale',
        'delivery_date',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(tbluser::class, 'user_ID', 'user_ID');
    }
    public function orderdetails()
    {
        return $this->belongsTo(tblorderdetails::class, 'ordDet_ID', 'ordDet_ID');
    }
}
