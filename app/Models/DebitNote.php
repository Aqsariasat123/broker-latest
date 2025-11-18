<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DebitNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_plan_id',
        'debit_note_no',
        'issued_on',
        'amount',
        'status',
        'document_path',
    ];

    protected $casts = [
        'issued_on' => 'date',
        'amount' => 'decimal:2',
    ];

    public function paymentPlan(): BelongsTo
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'debit_note_id');
    }
}

