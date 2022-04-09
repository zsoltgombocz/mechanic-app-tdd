<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worksheet extends Model
{
    protected $fillable = ['admin_id'];

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    }

    public function mechanic()
    {
        return $this->hasOne(User::class, 'id', 'mechanic_id');
    }
    use HasFactory;
}
