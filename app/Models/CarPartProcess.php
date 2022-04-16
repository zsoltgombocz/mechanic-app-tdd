<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPartProcess extends Model
{
    protected $fillable = ['worksheet_id', 'name', 'serial', 'amount', 'price'];
    protected $table = 'used_car_parts';
    use HasFactory;
}
