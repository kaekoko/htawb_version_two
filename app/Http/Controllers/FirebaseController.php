<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase;
use Illuminate\Support\Facades\Log;

class FirebaseController extends Controller
{

    public function index($text)
    {
       $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . '/myvipwebsite-e2673-firebase-adminsdk-io6hx-b3bd31ba0b.json')
            ->withDatabaseUri('https://myvipwebsite-e2673-default-rtdb.asia-southeast1.firebasedatabase.app');

        $database = $firebase->createDatabase();

        return $database
            ->getReference('cashout')
            ->update(["title" => $text]);
    }
    // public function __construct()
    // {
    //     $this->database = \App\Services\FirebaseService::connect();
    // }

    // public function index()
    // {
    //     return response()->json($this->database->getReference('users/balances')->getValue());
    // }

    // public function createBalance($id, $balance)
    // {
    //     $this->database
    //         ->getReference('users/balances/' . $id)
    //         ->set([
    //             'id' => $id,
    //             'balance' => $balance,
    //             'last_provider' => 'PR',
    //             'last_balance' => '0'
    //         ]);

    //     return response()->json('user balance has been created');
    // }

    // public function deletecreateBalance($id, $balance, $last_provider, $oldbalance)
    // {
    //     $this->database
    //         ->getReference('users/balances/' . $id)
    //         ->set([
    //             'id' => $id,
    //             'balance' => $balance,
    //             'last_provider' => $last_provider,
    //             'last_balance' => $oldbalance
    //         ]);

    //     return response()->json('user balance has been created');
    // }

    // public function updateProvider($provider, $id, $last_balance)
    // {
    //     $this->database
    //         ->getReference('users/balances/' . $id)
    //         ->update([
    //             "balance" => 1,
    //             "id" => $id,
    //             "last_provider" => $provider,
    //             "last_balance" => $last_balance
    //         ]);
    // }

    // public function getAllValues()
    // {
    //     return $this->database
    //         ->getReference('users/balances')
    //         ->getValue();
    // }

    // public function getValue($id)
    // {
    //     return $this->database
    //         ->getReference('users/balances/' . $id)
    //         ->getValue();
    // }

    // public function removeValue($id)
    // {
    //     return $this->database
    //         ->getReference('users/balances/' . $id)
    //         ->remove();
    // }

    // public function totalbalance()
    // {
    //     $users = User::get();
    //     $data = [];
    //     foreach ($users as $user) {
    //         $getprovider = $this->getValue($user->id);
    //         $provider = $getprovider['last_provider'];
    //         $getbalance = getbalance($provider, $user->user_code, 'alexaung57');
    //         array_push($data, $getbalance['balance']);
    //     }

    //     return response()->json([
    //         "message" => "success",
    //         "data" => array_sum($data)
    //     ]);
    // }
      public function createBalance($id, $balance)
    {
        // $this->database
        //     ->getReference('users/balances/' . $id)
        //     ->set([
        //         'id' => $id,
        //         'balance' => $balance,
        //         'last_provider' => 'PR',
        //         'last_balance' => '0'
        //     ]);

        $update = User::where('id', $id)->update(['game_balance' => $balance,'last_provider' => 'PR',]);

        return response()->json('user balance has been created');
    }

    public function deletecreateBalance($id, $balance, $last_provider, $oldbalance)
    {
        // $this->database
        //     ->getReference('users/balances/' . $id)
        //     ->set([
        //         'id' => $id,
        //         'balance' => $balance,
        //         'last_provider' => $last_provider,
        //         'last_balance' => $oldbalance
        //     ]);

        return response()->json('user balance has been created');
    }

    public function updateProvider($provider, $id, $last_balance)
    {
        // $this->database
        //     ->getReference('users/balances/' . $id)
        //     ->update([
        //         "balance" => 1,
        //         "id" => $id,
        //         "last_provider" => $provider,
        //         "last_balance" => $last_balance
        //     ]);

        $update = User::where('id', $id)->update(['game_balance' => $last_balance,'last_provider' => $provider,]);
    }

    public function getAllValues()
    {
        $user=User::where('id', $id)->first(); 
        $userarr = ['last_provider' => $user->last_provider, 'game_balance' => $user->game_balance ];
        return $userarr;
    }

    public function getValue($id)
    {
        $user=User::where('id', $id)->first(); 
        $userarr = ['last_provider' => $user->last_provider, 'game_balance' => $user->game_balance ];
        return $userarr;
    }

    // public function removeValue($id)
    // {
    //     return $this->database
    //         ->getReference('users/balances/' . $id)
    //         ->remove();
    // }

    public function totalbalance()
    {
        $users = User::get();
        $data = [];
        foreach ($users as $user) {
            $getprovider = $this->getValue($user->id);
            $provider = $getprovider['last_provider'];
            $getbalance = getbalance($provider, $user->user_code, 'alexaung57');
            array_push($data, $getbalance['balance']);
        }

        return response()->json([
            "message" => "success",
            "data" => array_sum($data)
        ]);
    }
    public function c2dLiveData($array)
    {
        $data = $this->database->getReference('number/data')->getValue();
        if (!empty($data)) {
            $this->database->getReference('number/data')
                ->update([
                    'number' => $array['number'],
                    'buy' => $array['buy']
                ]);
        } else {
            $this->database
                ->getReference('number/data')
                ->set([
                    'number' => $array['number'],
                    'buy' => $array['buy']
                ]);
        }

        Log::info('Crypto Live Data.');
    }
}