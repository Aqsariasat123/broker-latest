<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'payee',
        'date_paid',
        'amount_paid',
        'description',
        'category',
        'mode_of_payment',
        'expense_notes'
    ];

    protected $casts = [
        'date_paid' => 'date',
        'amount_paid' => 'decimal:2'
    ];
}