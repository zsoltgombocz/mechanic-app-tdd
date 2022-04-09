<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['name', 'role_id', 'login_id', 'password'];
    public $timestamps = false;

    public function created_worksheets()
    {
        return $this->hasMany(Worksheet::class, 'admin_id');
    }

    public function assigned_worksheets()
    {
        return $this->hasMany(Worksheet::class, 'mechanic_id');
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    use HasFactory;
}
