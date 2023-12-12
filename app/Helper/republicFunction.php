<?php

namespace App\Helper;

use App\Models\OneD;
use App\Models\TwoD;
use App\Models\User;
use App\Models\Agent;
use App\Models\ThreeD;
use App\Models\Betting;
use App\Models\UserBet;
use App\Models\Betting1d;
use App\Models\Betting3d;
use App\Models\CryptoOneD;
use App\Models\CryptoTwoD;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use App\Models\NumberSetting;
use App\Models\OverAllSetting;
use App\Models\BettingCrypto1d;
use App\Models\BettingCrypto2d;
use App\Models\NumberSetting1d;
use App\Models\NumberSetting3d;
use App\Models\NumberSettingc1d;
use Illuminate\Support\Facades\Auth;
use App\Models\NumberSettingCrypto2d;
use phpDocumentor\Reflection\DocBlock\Tags\See;

class republicFunction
{
    public static function getNumberInfo($time, $date)
    {
        $numbers = array();

        $twoDs = TwoD::get(['id','bet_number','default_amount']);

        $hot_block = NumberSetting::whereDate('created_at', $date)->where('section', $time)->get();
        $hot = $hot_block->where('type', 'hot')->first();
        $block = $hot_block->where('type', 'block')->first();

        $bettings = Betting::whereDate('date', $date)->where('section', $time)->get();

        foreach($twoDs as $twoD)
        {
            $isHot = 0; $isBlock = 'no';
            if($hot != null && $block != null){
                $isHot = in_array($twoD->bet_number,explode(',',$hot->hot_number)) ? $hot->hot_amount : 0;
                $isBlock = in_array($twoD->bet_number,explode(',',$block->block_number)) ? 'yes' : 'no';
            }

            $total_bet_amount = $bettings->where('bet_number', $twoD->bet_number)->pluck('amount')->toArray();
            $n_array = [
                'id' => $twoD->id,
                'bet_number' => $twoD->bet_number,
                'default_amount' => $twoD->default_amount,
                'total_amount' => array_sum($total_bet_amount),
                'hot_amount' => $isHot,
                'block' => $isBlock
            ];
            array_push($numbers, $n_array);
        }
        return $numbers;
    }

    public static function getNumberInfo1d($time, $date)
    {
        $numbers = array();

        $twoDs = OneD::get(['id','bet_number','default_amount']);

        $hot_block = NumberSetting1d::whereDate('created_at', $date)->where('section', $time)->get();
        $hot = $hot_block->where('type', 'hot')->first();
        $block = $hot_block->where('type', 'block')->first();

        $bettings = Betting1d::whereDate('date', $date)->where('section', $time)->get();

        foreach($twoDs as $twoD)
        {
            $isHot = 0; $isBlock = 'no';
            if($hot != null && $block != null){
                $isHot = in_array($twoD->bet_number,explode(',',$hot->hot_number)) ? $hot->hot_amount : 0;
                $isBlock = in_array($twoD->bet_number,explode(',',$block->block_number)) ? 'yes' : 'no';
            }

            $total_bet_amount = $bettings->where('bet_number', $twoD->bet_number)->pluck('amount')->toArray();
            $n_array = [
                'id' => $twoD->id,
                'bet_number' => $twoD->bet_number,
                'default_amount' => $twoD->default_amount,
                'total_amount' => array_sum($total_bet_amount),
                'hot_amount' => $isHot,
                'block' => $isBlock
            ];
            array_push($numbers, $n_array);
        }
        return $numbers;
    }

    public static function getNumberInfo3d($month)
    {
        $numbers = array();

        $threeDs = ThreeD::get(['id','bet_number']);

        $isHot = 0;
        $isBlock = 'no';
        $hots = NumberSetting3d::where('type', 'hot')->get(['hot_number','hot_amount']);
        $blocks = NumberSetting3d::where('type', 'block')->get('block_number');

        $bettings = Betting3d::whereMonth('date', $month)->get();

        foreach($threeDs as $threeD)
        {
            foreach ($hots as $hot){
                $isHot = in_array($threeD->bet_number,explode(',',$hot->hot_number)) ? $hot->hot_amount : 0;
                if($isHot != 0)
                {
                    break;
                }
            }
            foreach ($blocks as $block){
                $isBlock = in_array($threeD->bet_number,explode(',',$block->block_number)) ? 'yes' : 'no';
                if($isBlock != 'no')
                {
                    break;
                }
            }
            $total_bet_amount = $bettings->where('bet_number', $threeD->bet_number)->pluck('amount')->toArray();
            $n_array = [
                'id' => $threeD->id,
                'bet_number' => $threeD->bet_number,
                'total_amount' => array_sum($total_bet_amount),
                'hot_amount' => $isHot,
                'block' => $isBlock,
            ];
            array_push($numbers, $n_array);
        }
        return $numbers;
    }

    public static function dailyPaymentStatement($date, $column, $user, $sma_id = 0)
    {
        $main_model = (new self)->getMianModel($column);

        $data = (new self)->query($date, $main_model, $user, $sma_id);

        return response()->json([
            "message" => "success",
            "result" => $data
        ]);
    }

    public static function dailyPaymentStatementPerSection($date, $column, $user, $time, $id = 0, $sma_id = 0)
    {
        $main_model = (new self)->getMianModel($column);

        $data = (new self)->query($date, $main_model, $user, $sma_id, $time, $id);

        return response()->json([
            "message" => "success",
            "result" => $data
        ]);
    }

    private function getMianModel($column)
    {
        if($column == 'senior_agent_id')
        {
            return [
                'model' => new SeniorAgent(),
                'type' => 'senior_agent_id'
            ];
        }
        else if($column == 'master_agent_id')
        {
            return [
                'model' => new MasterAgent(),
                'type' => 'master_agent_id'
            ];
        }
        else if($column == 'agent_id')
        {
            return [
                'model' => new Agent(),
                'type' => 'agent_id'
            ];
        }
        else if($column == 'user')
        {
            return [
                'model' => new User(),
                'type' => 'user_id'
            ];
        }
    }

    private function query($date, $model, $user, $sma_id, $time = null, $id = 0)
    {
        if($user == 'super_admin')
        {
            if($time != null)
            {
                $superadmin_relations = $model['model']::where('super_admin_id','!=', null)->where('id', $id)->get();
            }
            else
            {
                $superadmin_relations = $model['model']::where('super_admin_id','!=', null)->get();
            }

            $medium_query = $this->mediumQuery($superadmin_relations, $date, $model['type'], $time);
            return $medium_query;
        }
        else if($user == 'senior')
        {
            if($time != null)
            {
                $senior_agents = $model['model']::where('id', $id)->get();
            }
            else
            {
                $senior_agents = $model['model']::where('senior_agent_id', $sma_id)->get();
            }

            $medium_query = $this->mediumQuery($senior_agents, $date, $model['type'], $time);
            return $medium_query;
        }
        else if($user == 'master')
        {
            if($time != null)
            {
                $master_agents = $model['model']::where('id', $id)->get();
            }
            else
            {
                $master_agents = $model['model']::where('master_agent_id', $sma_id)->get();
            }

            $medium_query = $this->mediumQuery($master_agents, $date, $model['type'], $time);
            return $medium_query;
        }
        else if($user == 'agent')
        {
            if($time != null)
            {
                $agents = $model['model']::where('id', $id)->get();
            }
            else
            {
                $agents = $model['model']::where('agent_id', $sma_id)->get();
            }


            $medium_query = $this->mediumQuery($agents, $date, $model['type'], $time);
            return $medium_query;
        }
    }

    private function mediumQuery($relations, $date, $model, $time)
    {
        $queries = array();

        foreach($relations as $relation)
        {
            $deep_q = $this->deepQuery($date, $relation, $model, $time);

            if($model == 'user_id')
            {
                if($deep_q['reward'] > 0)
                {
                    array_push($queries, $deep_q);
                }
            }
            else
            {
                if($deep_q['to_get'] > 0)
                {
                    array_push($queries, $deep_q);
                }
                 // array_push($queries, $deep_q);
            }


        }
        return $queries;
    }

    private function deepQuery($date, $relation, $type, $time)
    {
        $SA_id = $MA_id = $A_id = $b_users = array();

        if($type == 'senior_agent_id' || count($SA_id) > 0)
        {
            if($type == 'senior_agent_id')
            {
                $SA_id = [$relation->id];
            }
            $MA_id = MasterAgent::whereIn($type, $SA_id)->pluck('id');
        }
        if($type == 'master_agent_id' || count($MA_id) > 0)
        {
            if($type == 'master_agent_id')
            {
                $MA_id = [$relation->id];
            }
            $type = 'master_agent_id';
            $A_id = Agent::whereIn($type, $MA_id)->pluck('id');
        }

        if($type == 'agent_id' || count($A_id) > 0)
        {
            if($type == 'agent_id')
            {
                $A_id = [$relation->id];
            }
            $type = 'agent_id';
            $b_users = User::whereIn($type, $A_id)->pluck('id');
        }

        if($type == 'user_id' || count($b_users) > 0)
        {
            if($type == 'user_id')
            {
                $b_users = [$relation->id];
            }
        }

        $users = User::orWhereIn('senior_agent_id', $SA_id)->orWhereIn('master_agent_id', $MA_id)->orWhereIn('agent_id', $A_id)->orWhereIn('id', $b_users)->pluck('id');
        if($time != null)
        {
            $user_bets = UserBet::whereDate('date', $date)->WhereIn('user_id', $users)->where('section', $time)->get();
        }
        else
        {
            $user_bets = UserBet::whereDate('date', $date)->WhereIn('user_id', $users)->get();
        }

        $all_betting_amount = array();
        $all_reward_amount = array();

        foreach($user_bets as $ub)
        {
            array_push($all_betting_amount, $ub->total_amount);
            array_push($all_reward_amount, $ub->reward_amount);
        }

        $to_get = array_sum($all_betting_amount);
        $to_pay = array_sum($all_reward_amount);
        $commission  = ($to_get * $relation->percent) / 100;
        $result = $to_get - ($to_pay + $commission);

        if($type == 'user_id')
        {
            $data = [
                "id" => $relation->id,
                "name" => $relation->name,
                "phone" => $relation->phone,
                "reward" =>  $to_pay, // reward
            ];
        }
        else
        {
            $data = [
                "id" => $relation->id,
                "name" => $relation->name,
                "phone" => $relation->phone,
                "percent" => $relation->percent,
                "to_get" => $to_get, //total amount of betting
                "commission" => $commission, // commission to give
                "to_pay" =>  $to_pay, // reward
                "result" => $result // to_get - (commission + reward)
            ];
        }

        return $data;
    }

    public static function getNumberInfocrypto2d($time, $date)
    {
        $numbers = array();

        $twoDs = CryptoTwoD::get(['id','bet_number','default_amount']);

        $hot_block = NumberSettingCrypto2d::whereDate('created_at', $date)->where('section', $time)->get();

        $hot = $hot_block->where('type', 'hot')->first();
        $block = $hot_block->where('type', 'block')->first();

        $bettings = BettingCrypto2d::whereDate('date', $date)->where('section', $time)->get();

        foreach($twoDs as $twoD)
        {
            $isHot = 0; $isBlock = 'no';
            if($hot != null && $block != null){
                $isHot = in_array($twoD->bet_number,explode(',',$hot->hot_number)) ? $hot->hot_amount : 0;
                $isBlock = in_array($twoD->bet_number,explode(',',$block->block_number)) ? 'yes' : 'no';
            }

            $total_bet_amount = $bettings->where('bet_number', $twoD->bet_number)->pluck('amount')->toArray();
            $n_array = [
                'id' => $twoD->id,
                'bet_number' => $twoD->bet_number,
                'default_amount' => $twoD->default_amount,
                'total_amount' => array_sum($total_bet_amount),
                'hot_amount' => $isHot,
                'block' => $isBlock
            ];
            array_push($numbers, $n_array);
        }
        return $numbers;
    }

    public static function getNumberInfocrypto1d($time, $date)
    {
        $numbers = array();

        $twoDs = CryptoOneD::get(['id','bet_number','default_amount']);

        $hot_block = NumberSettingc1d::whereDate('created_at', $date)->where('section', $time)->get();

        $hot = $hot_block->where('type', 'hot')->first();
        $block = $hot_block->where('type', 'block')->first();

        $bettings = BettingCrypto1d::whereDate('date', $date)->where('section', $time)->get();

        foreach($twoDs as $twoD)
        {
            $isHot = 0; $isBlock = 'no';
            if($hot != null && $block != null){
                $isHot = in_array($twoD->bet_number,explode(',',$hot->hot_number)) ? $hot->hot_amount : 0;
                $isBlock = in_array($twoD->bet_number,explode(',',$block->block_number)) ? 'yes' : 'no';
            }

            $total_bet_amount = $bettings->where('bet_number', $twoD->bet_number)->pluck('amount')->toArray();
            $n_array = [
                'id' => $twoD->id,
                'bet_number' => $twoD->bet_number,
                'default_amount' => $twoD->default_amount,
                'total_amount' => array_sum($total_bet_amount),
                'hot_amount' => $isHot,
                'block' => $isBlock
            ];
            array_push($numbers, $n_array);
        }
        return $numbers;
    }
}
