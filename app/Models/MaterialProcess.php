<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialProcess extends Model
{
    protected $fillable = ['worksheet_id', 'name', 'amount', 'price'];
    protected $table = 'used_materials';
    use HasFactory;
}
