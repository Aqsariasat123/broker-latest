<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'income_id', 'income_source_id', 'date_rcvd', 'amount_received', 'description',
        'category', 'mode_of_payment_id', 'statement_no', 'income_notes'
    ];

    protected $dates = ['date_rcvd'];

    public function incomeSource()
    {
        return $this->belongsTo(\App\Models\LookupValue::class, 'income_source_id');
    }

    public function modeOfPayment()
    {
        return $this->belongsTo(\App\Models\LookupValue::class, 'mode_of_payment_id');
    }
}
