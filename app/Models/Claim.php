<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id', 'policy_no', 'client_name', 'loss_date', 'claim_date', 'claim_amount',
        'claim_summary', 'status', 'close_date', 'paid_amount', 'settlment_notes'
    ];

    protected $dates = ['loss_date', 'claim_date', 'close_date'];
}
