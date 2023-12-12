<?php

namespace App\Helper;

use App\Models\Agent;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use App\Models\SuperAdmin;

class searchAgentsModel
{

    public static function getAgentID($object)
    {
        $data = [
            'agent_object' => null,
            'master_agent_object' => null,
            'senior_agent_object' => null,
            'super_admin_object' => null
        ];

        $load = $object;

        while($data['super_admin_object'] == null)
        {  
            $load_data = (new self)->getAgentModel($load);
            $data[$load_data['column']] = $load_data['object'];
            $load = $load_data['object'];
        }
        return $data;
    }

    private function getAgentModel($object)
    {
        if(isset($object) && $object->agent_id)
        {
            $agent = Agent::find($object->agent_id);
            return $this->modelQuery('Agent', 'agent_object', $agent);
        }
        else if(isset($object) && $object->master_agent_id)
        {
            $master_agent = MasterAgent::find($object->master_agent_id);
            return $this->modelQuery('MasterAgent', 'master_agent_object', $master_agent);
        }
        else if(isset($object) && $object->senior_agent_id)
        {
            $senior_agent = SeniorAgent::find($object->senior_agent_id);
            return $this->modelQuery('SeniorAgent', 'senior_agent_object', $senior_agent);
        }
        else if(isset($object) && $object->super_admin_id)
        {
            $super_admin = SuperAdmin::find($object->super_admin_id);
            return $this->modelQuery('SuperAdmin', 'super_admin_object', $super_admin);
        }
    }

    private function modelQuery($model, $column, $aCollection)
    {
        return $data = [
            'model' => $model,
            'column' => $column,
            'object' => $aCollection
        ];
    }
}
