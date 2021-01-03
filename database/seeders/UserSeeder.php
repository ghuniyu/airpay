<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $token = Str::random(80);
        $token = 'Sample-BearerToken';
        User::create([
            'role_id' => 'airpay',
            'name' => 'The Airpay',
            'email' => 'me@airpay.fun',
            'password' => bcrypt('airpay'),
            'api_token' => hash('sha256', $token)
        ]);
    }
}
