<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenewalNotice extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'rnid',
        'notice_date',
        'status',
        'delivery_method',
        'document_path',
        'remarks',
    ];

    protected $casts = [
        'notice_date' => 'date',
    ];

    /**
     * Get the policy that owns the renewal notice.
     */
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
