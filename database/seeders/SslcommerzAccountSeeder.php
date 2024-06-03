<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\sslcommerz_account;

class SslcommerzAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        sslcommerz_account::create([
            'store_id' => 'moder665cae2766c24',
            'store_passwd' => 'moder665cae2766c24@ssl',
            'init_url' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php', // or the appropriate live URL
            'currency' => 'BDT',
            'success_url' => 'https://your_success_url',
            'fail_url' => 'https://your_fail_url',
            'cancel_url' => 'https://your_cancel_url',
            'ipn_url' => 'https://your_ipn_url',
        ]);
    }
}
