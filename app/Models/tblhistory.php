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
    
}
