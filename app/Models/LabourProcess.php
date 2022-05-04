<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourProcess extends Model
{
    protected $fillable = ['worksheet_id', 'maintenance_id', 'time_span', 'price', 'name', 'info'];
    protected $table = 'labour_processes';

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class, 'maintenance', 'id');
    }
    use HasFactory;
}
