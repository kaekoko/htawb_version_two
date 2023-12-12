<?php

namespace App\Helper;

use App\Models\Agent;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use App\Models\SuperAdmin;

class percentAndCommission
{
    public static function getCommissionData($agentData, $betslip_id, $total_amount, $section, $user_id)
    {
        $agent_percent = (new self)->getCommissionPercent($agentData['agent_object']);
        $master_agent_percent = (new self)->getCommissionPercent($agentData['master_agent_object']);
        $senior_agent_percent = (new self)->getCommissionPercent($agentData['senior_agent_object']);

        $all_agent_percent = [
            'agent_p' =>  $agent_percent['percent'],
            'master_agent_p' => $master_agent_percent['percent'],
            'senior_agent_p' => $senior_agent_percent['percent']
        ];
        
        $agent_commission = (new self)->getCommissionAmount($all_agent_percent, $total_amount, 'agent');
        $master_agent_commission = (new self)->getCommissionAmount($all_agent_percent, $total_amount, 'master_agent');
        $senior_agent_commission = (new self)->getCommissionAmount($all_agent_percent, $total_amount, 'senior_agent');

        (new self)->addCommissionToAgents($agentData['agent_object'], $agent_commission);
        (new self)->addCommissionToAgents($agentData['master_agent_object'], $master_agent_commission);
        (new self)->addCommissionToAgents($agentData['senior_agent_object'], $senior_agent_commission);


        $data = [
            'senior_agent_id' => $senior_agent_percent['agentID'],
            'senior_agent_commission' =>  $senior_agent_commission,
            'master_agent_id' => $master_agent_percent['agentID'],
            'master_agent_commission' =>  $master_agent_commission,
            'agent_id' => $agent_percent['agentID'],
            'agent_commission' =>  $agent_commission,
            'total_commission' => ($agent_commission + $master_agent_commission + $senior_agent_commission),
            'user_id' => $user_id,
            'bet_slip_id' => $betslip_id,
            'section' => $section,
        ];

        return $data;
    }

    private function getCommissionPercent($object)
    {
        if($object != null)
        {
            $ob_data = [
                'percent' => $object->percent,
                'agentID' => $object->id
            ];
            return $ob_data;
        }
        else
        {
            $ob_data = [
                'percent' => 0,
                'agentID' => null
            ];
            return $ob_data;
        }
    }

    private function getCommissionAmount($agent_percent, $total_amount, $agent_type)
    {
        switch ($agent_type) {
            case "agent":
                if($agent_percent['agent_p'] != 0)
                {
                    $commission = ($total_amount * $agent_percent['agent_p']) / 100;
                    return $commission;
                }
                else
                { 
                    return 0;
                } 
                break;
            case "master_agent":
                if($agent_percent['master_agent_p'] != 0)
                {
                    $commission = ($total_amount * ($agent_percent['master_agent_p'] - $agent_percent['agent_p'])) / 100;
                    return $commission;
                }
                else
                { 
                    return 0;
                } 
                break;
            case "senior_agent":
                if($agent_percent['senior_agent_p'] != 0)
                {
                    $commission = ($total_amount * ($agent_percent['senior_agent_p'] - $agent_percent['master_agent_p'])) / 100;
                    return $commission;
                }
                else
                { 
                  return 0;
                } 
                break;
            default:
              return null;
        }
    }

    private function addCommissionToAgents($object, $commission)
    {
        if($object != null)
        {
            $object->commission += $commission;
            $object->save();
        }   
        return 'success';
    }
}