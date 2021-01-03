<?php

namespace App\Models;

use App\Traits\UuidIndex;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function withdraw()
    {
        if ($this['balance'] == 0) {
            return [
                'success' => false,
                'balance' => 'insufficient balance'
            ];
        } else {
            DB::beginTransaction();
            try {
                Transaction::create([
                    'user_id' => $this->user->id,
                    'type' => 'debit',
                    'info' => "Requested by User",
                    'note' => "Withdraw Successful",
                    'gross_amount' => $this['balance'],
                    'payment_method_id' => 'airpay',
                    'status' => 'success',
                ]);

                $this->debit($this['balance']);

                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
    }

    public function buy($what, $howMuch)
    {
        $what = Str::title($what);
        if ($this['balance'] < $howMuch) {
            return -1;
        } else {
            DB::beginTransaction();
            try {
                Transaction::create([
                    'user_id' => $this->user->id,
                    'type' => 'debit',
                    'info' => "PPOB Payment by User",
                    'note' => "Pay PPOB $what Successful",
                    'gross_amount' => $howMuch,
                    'payment_method_id' => 'airpay',
                    'status' => 'success',
                ]);

                $this->debit($howMuch);

                DB::commit();
                return 1;
            } catch (\Exception $e) {
                DB::rollBack();
                return 0;
            }
        }
    }
}
