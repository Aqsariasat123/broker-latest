<?php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'category',
        'description',
        'name',
        'contact_no',
        'due_date',
        'due_time',
        'date_in',
        'assignee',
        'task_status',
        'date_done',
        'repeat',
        'frequency',
        'rpt_date',
        'rpt_stop_date',
        'task_notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'date_done' => 'date',
        'rpt_date' => 'date',
        'rpt_stop_date' => 'date',
        'repeat' => 'boolean',
    ];

    public function isOverdue()
    {
        return $this->due_date < now()->format('Y-m-d') && $this->task_status !== 'Completed';
    }

    public static function generateTaskId()
    {
        $latest = self::where('task_id', 'like', 'TK%')->orderBy('id', 'desc')->first();
        if (!$latest) {
            return 'TK24001';
        }
        
        $number = intval(substr($latest->task_id, 2)) + 1;
        return 'TK' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}