<?php

namespace App\Helper;

use Pusher\Pusher;
use App\Models\User;
use App\Models\Agent;
use App\Models\SuperAdmin;
use App\Models\MasterAgent;
use App\Models\SeniorAgent;
use Illuminate\Http\Request;

class checkPhone
{
    public static function number()
    {
        $super_admin = SuperAdmin::where('phone',request()->phone)->first();
        $user = User::where('phone',request()->phone)->first();
        $check_phone = [$super_admin,$user];
        return count(array_filter($check_phone));
    }


   
}
