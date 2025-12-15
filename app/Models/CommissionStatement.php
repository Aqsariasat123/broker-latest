<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommissionStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_note_id',
        'com_stat_id',
        'period_start',
        'period_end',
        'net_commission',
        'tax_withheld',
        'attachment_path',
        'remarks',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'net_commission' => 'decimal:2',
        'tax_withheld' => 'decimal:2',
    ];

    /**
     * Get the commission note that owns the commission statement.
     */
    public function commissionNote(): BelongsTo
    {
        return $this->belongsTo(CommissionNote::class);
    }

    /**
     * Get the commissions for the commission statement.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Get the incomes for the commission statement.
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class, 'commission_statement_id');
    }
}
