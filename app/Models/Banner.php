<?php

namespace App\Models;

use App\Traits\ActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    use ActiveScopeTrait;
}
