<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $diffWithGMT=6*60*60+30*60;
        $date = gmdate('Y-m-d', time()+$diffWithGMT);
        $times = Section::where("is_open", 1)->pluck('time_section')->toArray();
        foreach($times as $time){
            $t = date("h:i A", strtotime($time));
            DB::table('custom_records')->insert([
                'twod_number' => '-',
                'record_time' => $t,
                'record_date' => $date
            ]);
        }
    }
}
