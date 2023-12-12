<?php

namespace App\Invoker;

use App\Helper\helper;
use App\Models\ThreeD;
use App\Models\Betting3d;
use App\Models\Section3d;
use App\Models\UserBet3d;
use App\Models\UserReferHistory;
use Illuminate\Support\Facades\Log;

class invoke3D
{

    public static function months()
    {
        return [
            "01" => "January",
            "02" => "February",
            "03" => "March",
            "04" => "April",
            "05" => "May",
            "06" => "June",
            "07" => "July",
            "08" => "August",
            "09" => "September",
            "10" => "October",
            "11" => "November",
            "12" => "December"
        ];
    }

    public static function years()
    {
        $years = range(date("Y"), 2021);
        return $years;
    }

    public static function numbers()
    {
        $result = ThreeD::get();
        return $result;
    }

    public static function betDate3d($date)
    {
        $now_date = date('d', strtotime($date));
        $sections = Section3d::pluck('date');
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        // section 0: 28, 29, 30, 31, 01, 02
        // section 1: 14, 15, 16, 17
        if ($sections[0] == $now_date || $sections[1] == $now_date) {
            if ($sections[0] == $now_date) {
                $bet_date = $year . '-' . $month . '-' . $sections[0];
            } else {
                $bet_date = $year . '-' . $month . '-' . $sections[1];
            }
        }else{
            if ($sections[1] > $now_date) {
                $section_0 = $sections[0] == '01' > $now_date || $sections[0] == '02' > $now_date;
            } else {
                $section_0 = $sections[0] > $now_date;
            }
            if ($section_0) {
                $bet_date = $year . '-' . $month . '-' . $sections[0];
            } elseif ($sections[1] > $now_date) {
                $bet_date = $year . '-' . $month . '-' . $sections[1];
            } else {
                if ($month == 12) {
                    $bet_date = ((int)$year + (int)'1') . '-01-' . $sections[0];
                } else {
                    $bet_date = $year . '-' . ((int)$month + (int)'1') . '-' . $sections[0];
                }
            }
        }
        return $bet_date;
    }

    public static function editSectionChangeBetDate3D($update, $search, $id)
    {
        $datetime = helper::currentDateTime();
        $month = date('m', strtotime($datetime['date']));
        $year = date('Y', strtotime($datetime['date']));
        $bet_date = $year . '-' . $month . '-' . $search;

        //month +
        $now_date = date('d', strtotime($datetime['date']));
        $sections = Section3d::pluck('date');
        if ($id == 1) {

            if ($search == '01' || $search == '02') {
                if ($search > $now_date) {
                    $bet_date = $year . '-' . $month . '-' . $search;
                } else {
                    if ($month == 12) {
                        $bet_date = ((int)$year + (int)'1') . '-01-' . $search;
                    } else {
                        $bet_date = $year . '-' . ((int)$month + (int)'1') . '-' . $search;
                    }
                }
            }

            if ($sections[1] > $now_date) {
                $section_0 = $update == '01' > $now_date || $update == '02' > $now_date;
            } else {
                $section_0 = $update > $now_date;
            }

            if ($section_0) {
                $update_date = $year . '-' . $month . '-' . $update;
            } elseif ($sections[1] > $now_date) {
                $update_date = $year . '-' . $month . '-' . $update;
            } else {
                if ($month == 12) {
                    $update_date = ((int)$year + (int)'1') . '-01-' . $update;
                } else {
                    $update_date = $year . '-' . ((int)$month + (int)'1') . '-' . $update;
                }
            }
        } else {
            $update_date = $year . '-' . $month . '-' . $update;
        }
        //month +
        $user_bets = UserBet3d::where('bet_date', $bet_date)->get();

        if (count($user_bets) > 0) {
            foreach ($user_bets as $user_bet) {
                $user_bet = UserBet3d::find($user_bet->id);
                $user_bet->bet_date = $update_date;
                $user_bet->date_3d = $update;
                $user_bet->update();
            }
        }

        $bettings = Betting3d::where('bet_date', $bet_date)->get();
        if (count($bettings) > 0) {
            foreach ($bettings as $betting) {
                $betting = Betting3d::find($betting->id);
                $betting->bet_date = $update_date;
                $betting->date_3d = $update;
                $betting->update();
            }
        }

        $user_referrals = UserReferHistory::where('bet_date_3d', $bet_date)->get();
        if (count($user_referrals) > 0) {
            foreach ($user_referrals as $user_referral) {
                $user_referral = UserReferHistory::find($user_referral->id);
                $user_referral->bet_date_3d = $update_date;
                $user_referral->section = $update;
                $user_referral->update();
            }
        }
    }

    public static function getSection3D($now_date)
    {
        // $now_date = '19';
        $section = Section3d::pluck('date');
        if ($now_date > $section[0] &&  $now_date < $section[1]) {
            $sec = $section[1];
        } elseif ($now_date < $section[0] &&  $now_date < $section[1]) {
            $sec = $section[1];
        } elseif ($now_date > $section[0] &&  $now_date > $section[1]) {
            $sec = $section[0];
        } elseif ($now_date < $section[0] &&  $now_date > $section[1]) {
            $sec = $section[0];
        } elseif ($now_date == $section[0]) {
            $sec = $section[0];
        } elseif ($now_date == $section[1]) {
            $sec = $section[1];
        }
        return $sec;
    }

    public static function luckyNumberSection3dCron($schedule)
    {
        $section_3d = Section3d::all();
        $fis = $section_3d[0]->date;
        $fis_sec = str_split($section_3d[0]->time, 5)[0];
        $sec = $section_3d[1]->date;
        $sec_sec = str_split($section_3d[1]->time, 5)[0];

        $schedule->command('lucky_number_3d:cron')->monthlyOn($fis, $fis_sec)->timezone('Asia/Yangon');
        $schedule->command('lucky_number_3d:cron')->monthlyOn($sec, $sec_sec)->timezone('Asia/Yangon');
    }

    public static function tot3DFunUpDown($num, $lucky)
    {
        // $num = '013';
        if ($lucky == '000') {
            if ($num == '001') {
                $result = 'yes';
            } elseif ($num == $lucky) {
                $result = 'no';
            } else {
                $result = 'no';
            }
        } elseif ($lucky == '999') {
            if ($num == '998') {
                $result = 'yes';
            } elseif ($num == $lucky) {
                $result = 'no';
            } else {
                $result = 'no';
            }
        } elseif ($lucky[0] == $lucky[1] && $lucky[1] == $lucky[2] && $lucky[0] == $lucky[2]) {
            if ($lucky + 1 == $num) {
                $result = 'yes';
            } elseif ($lucky - 1 == $num) {
                $result = 'yes';
            } else {
                $result = 'no';
            }
        } else {
            $arr_num = array($num[0], $num[1], $num[2]);
            $arr_lucky = array($lucky[0], $lucky[1], $lucky[2]);

            sort($arr_num);
            sort($arr_lucky);

            if ($arr_lucky == $arr_num) {
                $result = 'yes';
            } elseif ($lucky + 1 == $num) {
                $result = 'yes';
            } elseif ($lucky - 1 == $num) {
                $result = 'yes';
            } else {
                $result = 'no';
            }
        }

        return $result;
    }

    public static function tot3DFun($num, $lucky)
    {
        // $num = '013';
        $arr_num = array($num[0], $num[1], $num[2]);
        $arr_lucky = array($lucky[0], $lucky[1], $lucky[2]);

        sort($arr_num);
        sort($arr_lucky);

        if ($arr_lucky == $arr_num) {
            $result = 'yes';
        } else {
            $result = 'no';
        }

        return $result;
    }
}
