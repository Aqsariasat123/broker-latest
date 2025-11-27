<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id', 'income_code', 'commission_statement_id', 'income_source_id', 'date_rcvd', 'date_received', 'amount_received', 'description',
        'category', 'mode_of_payment_id', 'statement_no', 'bank_statement_path', 'income_notes', 'notes'
    ];

    protected $casts = [
        'date_rcvd' => 'date',
        'date_received' => 'date',
        'amount_received' => 'decimal:2'
    ];
    
    // Accessor to handle both column names
    public function getDateAttribute()
    {
        return $this->date_rcvd ?? $this->date_received ?? null;
    }

    public function incomeSource()
    {
        return $this->belongsTo(\App\Models\LookupValue::class, 'income_source_id');
    }

    public function modeOfPayment()
    {
        return $this->belongsTo(\App\Models\LookupValue::class, 'mode_of_payment_id');
    }
}
