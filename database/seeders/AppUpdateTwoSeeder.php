<?php

namespace Database\Seeders;

use App\Models\AppUpdatTwo;
use Illuminate\Database\Seeder;

class AppUpdateTwoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppUpdatTwo::create([
            'version_code' => '',
            'version_name' =>  '',
            'playstore' => '',
            'force_update' => 0,
            'wallet_hide_version' => 1
        ]);
    }
}
