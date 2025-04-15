<?php

namespace App\Models;

use App\Traits\ActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;
    use ActiveScopeTrait;

    protected $casts = [
        'date' => 'date',
    ];
}
