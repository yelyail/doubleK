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
        'supplier_name',
        'user_ID',
        'supplier_contact',
        'supplier_landline',
        'supplier_address',
        'supplier_email',
        'archive',
    ];


    public function inventories()
    {
        return $this->hasMany(tblinventory::class, 'supplier_ID', 'supplier_ID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_ID', 'user_ID');
    }
}

