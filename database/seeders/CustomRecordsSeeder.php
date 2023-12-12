<?php

namespace Database\Seeders;

use App\Models\CustomRecord;
use Illuminate\Database\Seeder;

class CustomRecordsSeeder extends Seeder
{
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
                CustomRecord::create([
                        'record_time' => '09:30 AM',
                        'twod_number' => '-'
                ]);
                CustomRecord::create([
                        'record_time' => '12:00 PM',
                        'twod_number' => '-'
                ]);
                CustomRecord::create([
                        'record_time' => '02:00 PM',
                        'twod_number' => '-'
                ]);
                CustomRecord::create([
                        'record_time' => '04:30 PM',
                        'twod_number' => '-'
                ]);
                CustomRecord::create([
                        'record_time' => '08:00 PM',
                        'twod_number' => '-'
                ]);
        }
}
