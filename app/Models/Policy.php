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

    public function insurer(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'insurer_id');
    }

    public function policyClass(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'policy_class_id');
    }

    public function policyPlan(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'policy_plan_id');
    }

    public function policyStatus(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'policy_status_id');
    }

    public function frequency(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'frequency_id');
    }

    public function businessType(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'business_type_id');
    }

    public function payPlan(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'pay_plan_lookup_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'agency_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(LookupValue::class, 'channel_id');
    }

    // Accessor methods to safely get relationship names
    public function getInsurerNameAttribute(): ?string
    {
        if (!$this->insurer_id) {
            return null;
        }
        try {
            if ($this->relationLoaded('insurer') && $this->insurer) {
                return (string) $this->insurer->name;
            }
            // Lazy load if not already loaded
            $insurer = $this->insurer;
            if ($insurer && is_object($insurer) && isset($insurer->name)) {
                return (string) $insurer->name;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getPolicyClassNameAttribute(): ?string
    {
        if (!$this->policy_class_id) {
            return null;
        }
        try {
            if ($this->relationLoaded('policyClass') && $this->policyClass) {
                return (string) $this->policyClass->name;
            }
            $policyClass = $this->policyClass;
            if ($policyClass && is_object($policyClass) && isset($policyClass->name)) {
                return (string) $policyClass->name;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getPolicyPlanNameAttribute(): ?string
    {
        if (!$this->policy_plan_id) {
            return null;
        }
        try {
            if ($this->relationLoaded('policyPlan') && $this->policyPlan) {
                return (string) $this->policyPlan->name;
            }
            $policyPlan = $this->policyPlan;
            if ($policyPlan && is_object($policyPlan) && isset($policyPlan->name)) {
                return (string) $policyPlan->name;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getPolicyStatusNameAttribute(): ?string
    {
        try {
            $policyStatus = $this->policyStatus;
            if ($policyStatus && is_object($policyStatus) && property_exists($policyStatus, 'name')) {
                $name = $policyStatus->name;
                return is_scalar($name) ? (string) $name : null;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getFrequencyNameAttribute(): ?string
    {
        try {
            $frequency = $this->frequency;
            if ($frequency && is_object($frequency) && property_exists($frequency, 'name')) {
                $name = $frequency->name;
                return is_scalar($name) ? (string) $name : null;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getBusinessTypeNameAttribute(): ?string
    {
        try {
            $businessType = $this->businessType;
            if ($businessType && is_object($businessType) && property_exists($businessType, 'name')) {
                $name = $businessType->name;
                return is_scalar($name) ? (string) $name : null;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getPayPlanNameAttribute(): ?string
    {
        try {
            $payPlan = $this->payPlan;
            if ($payPlan && is_object($payPlan) && property_exists($payPlan, 'name')) {
                $name = $payPlan->name;
                return is_scalar($name) ? (string) $name : null;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getAgencyNameAttribute(): ?string
    {
        try {
            $agency = $this->agency;
            if ($agency && is_object($agency) && property_exists($agency, 'name')) {
                $name = $agency->name;
                return is_scalar($name) ? (string) $name : null;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getClientNameAttribute(): ?string
    {
        if (!$this->client_id) {
            return null;
        }
        try {
            if ($this->relationLoaded('client') && $this->client) {
                return (string) $this->client->client_name;
            }
            $client = $this->client;
            if ($client && is_object($client) && isset($client->client_name)) {
                return (string) $client->client_name;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }

    public function getChannelNameAttribute(): ?string
    {
        try {
            $channel = $this->channel;
            if ($channel && is_object($channel) && property_exists($channel, 'name')) {
                $name = $channel->name;
                return is_scalar($name) ? (string) $name : null;
            }
        } catch (\Exception $e) {
            // Silently fail
        }
        return null;
    }
}