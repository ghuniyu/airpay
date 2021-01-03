<?php

namespace App\Models;

use App\Traits\UuidIndex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory, UuidIndex;

    protected $fillable = [
        'user_id',
        'balance'
    ];

    public function credit(int $amount)
    {
        $this['balance'] += $amount;
        $this->save();
        return [
            'success' => true,
            'balance' => $this['balance']
        ];
    }

    public function debit(int $amount)
    {
        if ($amount > $this['balance'])
            return [
                'success' => false,
                'balance' => 'insufficient balance'
            ];

        $this['balance'] -= $amount;
        $this->save();
        return [
            'success' => true,
            'balance' => $this['balance']
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
