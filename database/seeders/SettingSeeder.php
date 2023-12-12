<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    protected $settings = [
        [
            "key" => "cash_in_amount",
            "value" => ""
        ],
        [
            "key" => "cash_out_amount",
            "value" => ""
        ],
        [
            "key" => "cash_in_minimum_amount",
            "value" => ""
        ],
        [
            "key" => "cash_out_minimum_amount",
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
