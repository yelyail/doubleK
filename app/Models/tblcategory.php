<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblcategory extends Model
{
    use HasFactory;
    protected $table = 'tblcategory';
    protected $primaryKey = 'category_id';
    protected $fillable = [
        'categoryName',
        'categoryDescription',
        'categoryStatus',
    ];

    public function products()
    {
        return $this->hasMany(tblproduct::class, 'category_id', 'category_id');
    }
}

