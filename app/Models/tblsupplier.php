<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblsupplier extends Model
{
    use HasFactory;
    protected $table = 'tblsupplier';
    protected $primaryKey = 'supplier_ID';
    protected $fillable = [
        'user_ID',
        'supplier_name',
        'supplier_contact',
        'supplier_landline',
        'supplier_address',
        'supplier_email',
        'archived',
    ];
}
