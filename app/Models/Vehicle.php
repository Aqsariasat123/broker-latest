<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'regn_no', 'make', 'model', 'type', 'useage', 'year', 'value', 'policy_id',
        'engine', 'engine_type', 'cc', 'engine_no', 'chassis_no', 'from', 'to', 'notes'
    ];

    protected $dates = ['from', 'to'];
}
