<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Banking
        DB::table('payment_methods')->insert([
            'id' => '1',
            'name' => 'AYA Banking',
            'type' => 'banking'
        ]);
        DB::table('payment_methods')->insert([
            'id' => '2',
            'name' => 'KBZ Banking',
            'type' => 'banking'
        ]);
        DB::table('payment_methods')->insert([
            'id' => '3',
            'name' => 'CB Banking',
            'type' => 'banking'
        ]);
        // Mobile Pay
        DB::table('payment_methods')->insert([
            'id' => '4',
            'name' => 'AYA Pay',
            'type' => 'pay'
        ]);
        DB::table('payment_methods')->insert([
            'id' => '5',
            'name' => 'KBZ Pay',
            'type' => 'pay'
        ]);
        DB::table('payment_methods')->insert([
            'id' => '6',
            'name' => 'CB Pay',
            'type' => 'pay'
        ]);
        DB::table('payment_methods')->insert([
            'id' => '7',
            'name' => 'WAVE Pay',
            'type' => 'pay'
        ]);
        // Mobile Topup
        DB::table('payment_methods')->insert([
            'id' => '8',
            'name' => 'ATOM',
            'percentage' => 25,
            'type' => 'mobile-topup'
        ]);
        DB::table('payment_methods')->insert([
            'id' => '9',
            'name' => 'MPT',
            'percentage' => 25,
            'type' => 'mobile-topup'
        ]);
        DB::table('payment_methods')->insert([
            'id' => '10',
            'name' => 'Ooredoo',
            'percentage' => 25,
            'type' => 'mobile-topup'
        ]);
    }
}
