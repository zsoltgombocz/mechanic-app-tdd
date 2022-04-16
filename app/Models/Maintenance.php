<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;
    protected $table = 'maintenances';
    public function maintenance()
    {
        return $this->belongsTo(LabourProcess::class);
    }
    use HasFactory;
}
