<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    public function role()
    {
        return $this->belongsTo(User::class);
    }
    use HasFactory;
}