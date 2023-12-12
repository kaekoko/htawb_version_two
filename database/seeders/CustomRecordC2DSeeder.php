<?php

namespace Database\Seeders;

use App\Models\CustomRecordCrypto2D;
use Illuminate\Database\Seeder;

class CustomRecordC2DSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomRecordCrypto2D::create([
            'record_time' => '10:00 AM',
            'twod_number' => '-'
        ]);
        CustomRecordCrypto2D::create([
            'record_time' => '02:00 PM',
            'twod_number' => '-'
        ]);
        CustomRecordCrypto2D::create([
            'record_time' => '06:00 PM',
            'twod_number' => '-'
        ]);
        CustomRecordCrypto2D::create([
            'record_time' => '09:00 PM',
            'twod_number' => '-'
        ]);
    }
}
