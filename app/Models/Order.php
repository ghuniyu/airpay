<?php

namespace App\Models;

use App\Traits\UuidIndex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use UuidIndex;

    protected $fillable = [
        'user_id',
        'transaction_detail',
        'gross_amount',
        'status',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
