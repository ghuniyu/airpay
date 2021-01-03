<?php

namespace App\Models;

use App\Traits\UuidIndex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    use UuidIndex;

    protected $fillable = [
        'user_id',
        'type',
        'info',
        'note',
        'gross_amount',
        'payment_method_id',
        'payment_method_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
