<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheaterElement extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
        'active_from' => 'datetime',
        'active_to' => 'datetime'
    ];
}
