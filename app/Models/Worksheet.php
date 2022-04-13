<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worksheet extends Model
{
    protected $fillable = ['mechanic_id', 'admin_id', 'customer_name', 'customer_addr', 'vehicle_license', 'vehicle_brand', 'vehicle_model', 'closed', 'payment', 'closed_at'];

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    }

    public function mechanic()
    {
        return $this->hasOne(User::class, 'id', 'mechanic_id');
    }

    public function labour_process()
    {
        return $this->hasMany(LabourProcess::class);
    }
    use HasFactory;
}
