<?php
// app/Models/Contact.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_name',
        'contact_no',
        'type',
        'occupation',
        'employer',
        'acquired',
        'source',
        'status',
        'rank',
        'first_contact',
        'next_follow_up',
        'coid',
        'dob',
        'salutation',
        'source_name',
        'agency',
        'agent',
        'address',
        'email_address',
        'contact_id',
        'savings_budget',
        'married',
        'children',
        'children_details',
        'vehicle',
        'house',
        'business',
        'other'
    ];

    protected $casts = [
        'acquired' => 'date',
        'first_contact' => 'date',
        'next_follow_up' => 'date',
        'dob' => 'date',
        'married' => 'boolean',
        'savings_budget' => 'decimal:2'
    ];
}