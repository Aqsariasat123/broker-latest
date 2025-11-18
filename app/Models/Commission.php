<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_number', 'client_name', 'insurer_id', 'grouping', 'basic_premium', 'rate', 'amount_due',
        'payment_status_id', 'amount_rcvd', 'date_rcvd', 'state_no', 'mode_of_payment_id',
        'variance', 'reason', 'date_due', 'cnid'
    ];

    protected $dates = ['date_rcvd', 'date_due'];

    public function insurer()
    {
        return $this->belongsTo(\App\Models\LookupValue::class, 'insurer_id');
    }
    public function paymentStatus()
    {
        return $this->belongsTo(\App\Models\LookupValue::class, 'payment_status_id');
    }
    public function modeOfPayment()
    {
        return $this->belongsTo(\App\Models\LookupValue::class, 'mode_of_payment_id');
    }
}
