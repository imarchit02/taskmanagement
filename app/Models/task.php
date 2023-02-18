<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    use HasFactory;
    protected $table='task';
    protected $fillable = [
        'task_name',
        'description',
        'task_start_date',
        'task_end_date',
    ];
}
