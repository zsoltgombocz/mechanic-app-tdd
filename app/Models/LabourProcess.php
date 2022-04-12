<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourProcess extends Model
{
    protected $fillable = ['worksheet_id', 'maintenance_id', 'time_span'];
    use HasFactory;
}
