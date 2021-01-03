<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 'airpay',
                'name' => 'Airpay',
            ],
            [
                'id' => 'bank_transfer',
                'name' => 'Bank Transfer',
                'options' => json_encode([
                    'Bank Mandiri' => [
                        'Rekening' => '133713371337',
                        'Nama' => 'Airpay',
                    ],
                    'Bank BCA' => [
                        'Rekening' => '013337',
                        'Nama' => 'Airpay',
                    ],
                    'Bank BRI' => [
                        'Rekening' => '013337133337',
                        'Nama' => 'Airpay',
                    ],
                    'Bank BNI' => [
                        'Rekening' => '0133371337',
                        'Nama' => 'Airpay',
                    ],
                ])
            ],
            [
                'id' => 'e_wallet',
                'name' => 'E Wallet',
                'options' => json_encode([
                    'Go-Pay',
                    'OVO',
                    'LinkAja',
                ])
            ],
            [
                'id' => 'convenient_store',
                'name' => 'Convenient Store',
                'options' => json_encode([
                    'Indomart',
                    'Alfamart',
                    'Circle-K',
                ])
            ],
        ];

        foreach ($data as $item){
            PaymentMethod::create($item);
        }
    }
}
