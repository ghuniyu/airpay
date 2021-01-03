<?php

namespace App\Models;

use App\Traits\StringIndex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, StringIndex;

    protected $fillable = [
        'name'
    ];
}
