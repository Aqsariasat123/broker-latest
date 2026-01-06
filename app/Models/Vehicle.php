<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'regn_no', 'make', 'model', 'type', 'useage', 'year', 'value', 'policy_id',
        'engine', 'engine_type', 'cc', 'engine_no', 'chassis_no', 'from', 'to', 'notes','vehicle_seats','vehicle_color'
    ];

    protected $dates = ['from', 'to'];

    /**
     * Get the policy that owns the vehicle.
     */
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
