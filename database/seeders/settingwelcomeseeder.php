<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
class settingwelcomeseeder extends Seeder
{
    protected $settings = [
        [
            "key" => "welcome_bonus",
            "value" => ""
        ],
       
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->settings as $setting) {
            Setting::create($setting);
        }
    }
}
