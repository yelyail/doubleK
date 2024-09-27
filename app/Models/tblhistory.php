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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }

    public function orderReceipt()
    {
        return $this->belongsTo(tblorderreceipt::class, 'ordDet_ID', 'ordDet_ID');
    }
}

