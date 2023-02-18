<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class board extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_name',
        'board_desctiption',
        'start_date',
        'end_date',
    ];
}
