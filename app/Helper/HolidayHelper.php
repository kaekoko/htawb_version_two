<?php

namespace App\Helper;

use Carbon\Carbon;
use App\Models\LotteryOffDay;

class HolidayHelper{

    public static function isTheDayHoliday($dateTime, $dateArray)
    {
        $date =  date('Y-m-d', strtotime($dateTime));

        if(in_array($date, $dateArray))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public static function isThatWeekend($dateTime)
    {
        $date =  date('Y-m-d', strtotime($dateTime));

        $day = strtolower(date('l', strtotime($dateTime)));
        if($day == 'sunday' ||  $day == 'saturday')
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

}
