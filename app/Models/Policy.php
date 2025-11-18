<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'policy_no',
        'policy_code',
        'insurer_id',
        'policy_class_id',
        'policy_plan_id',
        'sum_insured',
        'start_date',
        'end_date',
        'insured',
        'insured_item',
        'policy_status_id',
        'date_registered',
        'renewable',
        'business_type_id',
        'term',
        'term_unit',
        'base_premium',
        'premium',
        'frequency_id',
        'pay_plan_lookup_id',
        'agency_id',
        'agent',
        'channel_id',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'date_registered' => 'date',
        'sum_insured' => 'decimal:2',
        'base_premium' => 'decimal:2',
        'premium' => 'decimal:2',
        'renewable' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function paymentPlans(): HasManyThrough
    {
        return $this->hasManyThrough(PaymentPlan::class, Schedule::class);
    }

    public function isDueForRenewal(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        $daysUntilRenewal = now()->diffInDays($this->end_date, false);
        return $daysUntilRenewal <= 30 && $daysUntilRenewal >= 0;
    }

    public function isExpired(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        return $this->end_date->isPast();
    }
}