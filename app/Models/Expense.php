<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_code',
        'payee',
        'date_paid',
        'amount_paid',
        'description',
        'category_id',
        'mode_of_payment_id',
        'attachment_path',
        'receipt_path',
        'notes'
    ];

    protected $casts = [
        'date_paid' => 'date',
        'amount_paid' => 'decimal:2'
    ];
}