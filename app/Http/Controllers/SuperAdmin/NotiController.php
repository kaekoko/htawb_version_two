<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Noti;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\NotiRequest;
use App\Http\Controllers\Controller;
use App\Models\CashIn;
use App\Models\CashOut;
use App\Models\UserBet;
use App\Models\UserBet3d;
use App\Models\UserBetCrypto2d;

class NotiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notis = Noti::orderBy('id', 'DESC')->get();
        return view('super_admin.noti.index', compact('notis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.noti.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotiRequest $request)
    {
        $noti = new Noti();
        $noti->title = $request->title;
        $noti->body = $request->body;
        $noti->save();
        return redirect('super_admin/noti')->with('flash_message', 'Create Notification');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $noti = Noti::findOrFail($id);
        return view('super_admin.noti.edit', compact('noti'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NotiRequest $request, $id)
    {
        $noti = Noti::findOrFail($id);
        $noti->title = $request->title;
        $noti->body = $request->body;
        $noti->update();
        return redirect('super_admin/noti')->with('flash_message', 'Update Notification');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Noti::find($id)->delete();
        return redirect('super_admin/noti')->with('flash_message', 'Noti Deleted');
    }

    public function updateNoti(Request $request, $id)
    {
        $noti_update = Noti::findorFail($id);

        $firebaseToken = User::whereNotNull("device_token")
        ->pluck('device_token')
        ->all();;

        $SERVER_API_KEY = 'AAAAY9kKSiQ:APA91bFLTAiseWMlnFx4Zyuyp0WTjthUQsXq54v4sfdM8nUpPh2i2Q0Cz7BK2c_zugvoCvzZFkRxxXvV_RM08yVluZtxQefa4n7KbvKVoDDsYPrwOosrmHmlkAgpFS1hd05qkUumPr87';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $noti_update->title,
                "body" => $noti_update->body,
                "icon" => asset("backend/noti.png"),
            ],
            "data" => [
                "title" => $noti_update->title,
                "body" => $noti_update->body,
                "icon" => asset("backend/noti.png"),
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        $data = json_decode($response, true);

        // dd($data);
        $noti_update->noti = $request->status;
        $noti_update->update();
        return redirect('super_admin/noti')->with('flash_message',' User Send Notification');
    }

    public function getAllNotiCount()
    {
        $today = date('Y-m-d');
        // BetSlips
        $bet_slip_count = UserBet::where('read', 0)->where('date', $today)->get();
        $bet_slip_3d_count = UserBet3d::where('read', 0)->where('date', $today)->get();
        $bet_slip_c2d_count = UserBetCrypto2d::where('read', 0)->where('date', $today)->get();
        // CashIn or Out
        $cash_in_count = CashIn::where('read', 0)->where('date', $today)->get();
        $cash_out_count = CashOut::where('read', 0)->where('date', $today)->get();
        // User
        $user_count =  User::where('read', 0)->get();
        return response()->json([
            "data" => [
                "bet_slip_count" => count($bet_slip_count),
                "bet_slip_3d_count" => count($bet_slip_3d_count),
                "bet_slip_c2d_count" => count($bet_slip_c2d_count),
                "cash_in_count" => count($cash_in_count),
                "cash_out_count" => count($cash_out_count),
                "user_count" => count($user_count)
            ]
        ]);
    }
}
