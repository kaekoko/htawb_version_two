<?php

namespace Database\Seeders;

use App\Models\AppUpdate;
use Illuminate\Database\Seeder;

class AppUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppUpdate::create([
            'version_code' => '',
            'version_name' =>  '',
            'playstore' => '',
            'force_update' => 0
        ]);
    }
}
