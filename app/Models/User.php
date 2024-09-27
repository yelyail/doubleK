<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory;
    protected $table = 'user';
    protected $primaryKey = 'user_ID';
    protected $fillable = [
        'fullname',
        'username',
        'jobtitle',
        'user_contact',
        'password',
        'archived',
    ];

    public function suppliers()
    {
        return $this->hasMany(tblsupplier::class, 'user_ID', 'user_ID');
    }
}

