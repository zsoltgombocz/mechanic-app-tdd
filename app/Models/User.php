<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name', 'role_id'];
    public $timestamps = false;
    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
    }

    public function roles()
    {
        return $this->hasOne(Role::class);
    }

    use HasFactory;
}
