<?php

namespace App\Http\Controllers\Api;

use App\Models\Section;
use App\Models\UserBet;
use App\Models\Section1d;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use App\Models\Sectionc1d;
use App\Models\SectionCrypto2d;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use App\Http\Controllers\Controller;

class WinnerController extends Controller
{
    public function winnerUsers($type)
    {
        // 3D
        if ($type == "3D") {
            $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
            $current_date = gmdate("Y-m-d", time() + $diffWithGMT);
            $prev_open_3d_date = UserBet3d::orderBy("bet_date", "Desc")
                ->where("win", 1)
                ->where("reward", 1)
                ->where("bet_date", "<=", $current_date)
                ->where("reward_amount", ">", 0)
                ->whereHas("bettings_3d", function ($query) { // only win, not include tot
                    $query->where("win", 1);
                    $query->where("tot", 0);
                })
                ->first();
            $prev_open_3d_date = !empty($prev_open_3d_date) ? $prev_open_3d_date->bet_date : null;
            $userbet_3d = UserBet3d::where("win", 1)
                ->where("reward", 1)
                ->where("bet_date", $prev_open_3d_date)
                ->where("reward_amount", ">", 0)
                ->whereHas("bettings_3d", function ($query) { // only win, not include tot
                    $query->where("win", 1);
                    $query->where("tot", 0);
                })
                ->get()
                ->groupBy("user_id");

            $groupWithUserId = $userbet_3d->map(function ($group) {
                $lucky_number = null;
                $win_amount = 0;
                $bet_amount = 0;
                foreach ($group as $data) {
                    $betting_3d = $data->bettings_3d->where("win", 1)
                        ->where("tot", 0); // only win, not include tot
                    $first_data = $betting_3d->first();
                    $win_amount += round($betting_3d->sum("amount") * $first_data->odd, 2);
                    $bet_amount += round($betting_3d->sum("amount"), 2);
                    $lucky_number = $first_data->bet_number;
                }
                return [
                    "id" => $group->first()->id,
                    "win_amount" => $win_amount,
                    "bet_amount" => $bet_amount,
                    "name" => $group->first()->user->name,
                    "phone" => $group->first()->user->phone ? "95*****" . substr($group->first()->user->phone, -3, 3) : "**********",
                    "section" => $group->first()->section,
                    "open_date" => $group->first()->bet_date,
                    "lucky_number" => $lucky_number
                ];
            });
            $collection = collect($groupWithUserId);
            $lists =  $collection->sortBy([
                ["win_amount", "desc"]
            ]);
            return response()->json([
                "data" => $lists,
                "section" => count($lists) > 0 ? $lists[0]["section"] : null,
                "open_date" => count($lists) > 0 ? $lists[0]["open_date"] : null,
                "lucky_number" => count($lists) > 0 ? $lists[0]["lucky_number"] : null
            ]);
        }
        // 2D
        if ($type == "2D") {
            $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
            $current_date = gmdate("Y-m-d", time() + $diffWithGMT);
            $prev_open_2d_date = UserBet::orderBy("date", "Desc")
                ->where("win", 1)
                ->where("reward", 1)
                ->where("date", "<=", $current_date)
                ->where("reward_amount", ">", 0)
                ->first();
            $prev_open_2d_date = !empty($prev_open_2d_date) ? $prev_open_2d_date->date : null;
            $sections = Section::orderBy("id", "Desc")
                ->where("is_open", 1)
                ->get();
            foreach ($sections as $section) {
                $time_section = date("h:i A", strtotime($section->time_section));
                $userbet_2d = UserBet::where("win", 1)
                    ->where("reward", 1)
                    ->where("section", $time_section)
                    ->where("date", $prev_open_2d_date)
                    ->where("reward_amount", ">", 0)
                    ->get()
                    ->groupBy("user_id");
                if (count($userbet_2d) > 0) {
                    $groupWithUserId = $userbet_2d->map(function ($group) {
                        $lucky_number = null;
                        $win_amount = 0;
                        $bet_amount = 0;
                        foreach ($group as $data) {
                            $bettings = $data->bettings->where("win", 1);
                            $first_data = $bettings->first();
                            $win_amount += round($bettings->sum("amount") * $first_data->odd, 2);
                            $bet_amount += round($bettings->sum("amount"), 2);
                            $lucky_number = $first_data->bet_number;
                        }
                        return [
                            "id" => $group->first()->id,
                            "win_amount" => $win_amount,
                            "bet_amount" => $bet_amount,
                            "name" => $group->first()->user->name,
                            "phone" => $group->first()->user->phone ? "95*****" . substr($group->first()->user->phone, -3, 3) : "**********",
                            "section" => $group->first()->section,
                            "open_date" => $group->first()->date,
                            "lucky_number" => $lucky_number
                        ];
                    });
                    $collection = collect($groupWithUserId);
                    $lists =  $collection->sortBy([
                        ["win_amount", "desc"]
                    ]);
                    return response()->json([
                        "data" => $lists,
                        "section" => count($lists) > 0 ? $lists[0]["section"] : null,
                        "open_date" => count($lists) > 0 ? $lists[0]["open_date"] : null,
                        "lucky_number" => count($lists) > 0 ? $lists[0]["lucky_number"] : null
                    ]);
                }
            }
        }
        // 1D
        if ($type == "1D") {
            $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
            $current_date = gmdate("Y-m-d", time() + $diffWithGMT);
            $prev_open_2d_date = UserBet1d::orderBy("date", "Desc")
                ->where("win", 1)
                ->where("reward", 1)
                ->where("date", "<=", $current_date)
                ->where("reward_amount", ">", 0)
                ->first();
            $prev_open_2d_date = !empty($prev_open_2d_date) ? $prev_open_2d_date->date : null;
            $sections = Section1d::orderBy("id", "Desc")
                ->where("is_open", 1)
                ->get();
            foreach ($sections as $section) {
                $time_section = date("h:i A", strtotime($section->time_section));
                $userbet_2d = UserBet1d::where("win", 1)
                    ->where("reward", 1)
                    ->where("section", $time_section)
                    ->where("date", $prev_open_2d_date)
                    ->where("reward_amount", ">", 0)
                    ->get()
                    ->groupBy("user_id");
                if (count($userbet_2d) > 0) {
                    $groupWithUserId = $userbet_2d->map(function ($group) {
                        $lucky_number = null;
                        $win_amount = 0;
                        $bet_amount = 0;
                        foreach ($group as $data) {
                            $bettings = $data->bettings->where("win", 1);
                            $first_data = $bettings->first();
                            $win_amount += round($bettings->sum("amount") * $first_data->odd, 2);
                            $bet_amount += round($bettings->sum("amount"), 2);
                            $lucky_number = $first_data->bet_number;
                        }
                        return [
                            "id" => $group->first()->id,
                            "win_amount" => $win_amount,
                            "bet_amount" => $bet_amount,
                            "name" => $group->first()->user->name,
                            "phone" => $group->first()->user->phone ? "95*****" . substr($group->first()->user->phone, -3, 3) : "**********",
                            "section" => $group->first()->section,
                            "open_date" => $group->first()->date,
                            "lucky_number" => $lucky_number
                        ];
                    });
                    $collection = collect($groupWithUserId);
                    $lists =  $collection->sortBy([
                        ["win_amount", "desc"]
                    ]);
                    return response()->json([
                        "data" => $lists,
                        "section" => count($lists) > 0 ? $lists[0]["section"] : null,
                        "open_date" => count($lists) > 0 ? $lists[0]["open_date"] : null,
                        "lucky_number" => count($lists) > 0 ? $lists[0]["lucky_number"] : null
                    ]);
                }
            }
        }
        // C2D
        if ($type == "C2D") {
            $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
            $current_date = gmdate("Y-m-d", time() + $diffWithGMT);
            $prev_open_c2d_date = UserBetCrypto2d::orderBy("date", "Desc")
                ->where("win", 1)
                ->where("reward", 1)
                ->where("date", "<=", $current_date) // current date or prev date
                ->where("reward_amount", ">", 0)
                ->first();
            $prev_open_c2d_date = !empty($prev_open_c2d_date) ? $prev_open_c2d_date->date : null;
            $sections = SectionCrypto2d::orderBy("id", "Desc")->get();
            foreach ($sections as $section) {
                $time_section = date("h:i A", strtotime($section->time_section));
                $userbet_c2d = UserBetCrypto2d::where("win", 1)
                    ->where("reward", 1)
                    ->where("section", $time_section)
                    ->where("date", $prev_open_c2d_date)
                    ->where("reward_amount", ">", 0)
                    ->get()
                    ->groupBy("user_id");
                if (count($userbet_c2d) > 0) {
                    $groupWithUserId = $userbet_c2d->map(function ($group) {
                        $lucky_number = null;
                        $win_amount = 0;
                        $bet_amount = 0;
                        foreach ($group as $data) {
                            $bettings_c2d = $data->bettings_c2d->where("win", 1);
                            $first_data = $bettings_c2d->first();
                            $win_amount += round($bettings_c2d->sum("amount") * $first_data->odd, 2);
                            $bet_amount += round($bettings_c2d->sum("amount"), 2);
                            $lucky_number = $first_data->bet_number;
                        }
                        return [
                            "id" => $group->first()->id,
                            "win_amount" => $win_amount,
                            "bet_amount" => $bet_amount,
                            "name" => $group->first()->user->name,
                            "phone" => $group->first()->user->phone ? "95*****" . substr($group->first()->user->phone, -3, 3) : "**********",
                            "section" => $group->first()->section,
                            "open_date" => $group->first()->date,
                            "lucky_number" => $lucky_number
                        ];
                    });
                    $collection = collect($groupWithUserId);
                    $lists =  $collection->sortBy([
                        ["win_amount", "desc"]
                    ]);
                    return response()->json([
                        "data" => $lists,
                        "section" => count($lists) > 0 ? $lists[0]["section"] : null,
                        "open_date" => count($lists) > 0 ? $lists[0]["open_date"] : null,
                        "lucky_number" => count($lists) > 0 ? $lists[0]["lucky_number"] : null
                    ]);
                }
            }
        }
        if ($type == "C1D") {
            $diffWithGMT = 6 * 60 * 60 + 30 * 60; // GMT + 06:30 ( Myanmar Time Zone )
            $current_date = gmdate("Y-m-d", time() + $diffWithGMT);
            $prev_open_c2d_date = UserBetCrypto1d::orderBy("date", "Desc")
                ->where("win", 1)
                ->where("reward", 1)
                ->where("date", "<=", $current_date) // current date or prev date
                ->where("reward_amount", ">", 0)
                ->first();
            $prev_open_c2d_date = !empty($prev_open_c2d_date) ? $prev_open_c2d_date->date : null;
            $sections = Sectionc1d::orderBy("id", "Desc")->where("is_open", 1)->get();
            foreach ($sections as $section) {
                $time_section = date("h:i A", strtotime($section->time_section));
                $userbet_c2d = UserBetCrypto1d::where("win", 1)
                    ->where("reward", 1)
                    ->where("section", $time_section)
                    ->where("date", $prev_open_c2d_date)
                    ->where("reward_amount", ">", 0)
                    ->get()
                    ->groupBy("user_id");
                if (count($userbet_c2d) > 0) {
                    $groupWithUserId = $userbet_c2d->map(function ($group) {
                        $lucky_number = null;
                        $win_amount = 0;
                        $bet_amount = 0;
                        foreach ($group as $data) {
                            $bettings_c2d = $data->bettings_c2d->where("win", 1);
                            $first_data = $bettings_c2d->first();
                            $win_amount += round($bettings_c2d->sum("amount") * $first_data->odd, 2);
                            $bet_amount += round($bettings_c2d->sum("amount"), 2);
                            $lucky_number = $first_data->bet_number;
                        }
                        return [
                            "id" => $group->first()->id,
                            "win_amount" => $win_amount,
                            "bet_amount" => $bet_amount,
                            "name" => $group->first()->user->name,
                            "phone" => $group->first()->user->phone ? "95*****" . substr($group->first()->user->phone, -3, 3) : "**********",
                            "section" => $group->first()->section,
                            "open_date" => $group->first()->date,
                            "lucky_number" => $lucky_number
                        ];
                    });
                    $collection = collect($groupWithUserId);
                    $lists =  $collection->sortBy([
                        ["win_amount", "desc"]
                    ]);
                    return response()->json([
                        "data" => $lists,
                        "section" => count($lists) > 0 ? $lists[0]["section"] : null,
                        "open_date" => count($lists) > 0 ? $lists[0]["open_date"] : null,
                        "lucky_number" => count($lists) > 0 ? $lists[0]["lucky_number"] : null
                    ]);
                }
            }
        }
        return response()->json([
            "data" => [],
            "section" =>  null,
            "open_date" =>  null,
            "lucky_number" => null
        ]);
    }
}
