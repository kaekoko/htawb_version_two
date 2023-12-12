<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Betting;
use App\Models\Section;
use App\Models\UserBet;
use App\Models\Category;
use App\Invoker\invoke3D;
use App\Models\Betting1d;
use App\Models\Betting3d;
use App\Models\UserBet1d;
use App\Models\UserBet3d;
use App\Invoker\invokeAll;
use App\Models\AutoRecord;
use App\Models\Sectionc1d;
use App\Models\LuckyNumber;
use App\Models\CustomRecord;
use Illuminate\Http\Request;
use App\Models\OverAllSetting;
use App\Models\BettingCrypto1d;
use App\Models\BettingCrypto2d;
use App\Models\SectionCrypto2d;
use App\Models\UserBetCrypto1d;
use App\Models\UserBetCrypto2d;
use App\Http\Controllers\Controller;
use App\Models\CustomRecordCrypto1D;
use App\Models\CustomRecordCrypto2D;
use App\Http\Requests\LuckyNumberRequest;
// use App\Http\Controllers\SuperAdmin\LuckyNumberController;

class LuckyNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->date) {
            $date = $request->date;
        } else {
            $date = Carbon::today();
        }

        $lucky_numbers = LuckyNumber::where('create_date', $date)->where('category_id', 1)->get();
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d g:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $format = strtotime($date[1] . ' ' . $date[2]);
        $customs = CustomRecord::get();
        $autos = AutoRecord::where('record_date', $date)->get();
        $format_date = strtotime($date[0]);
        $ten = strtotime('10 minutes');

        return view('super_admin.lucky_number.index', compact('lucky_numbers', 'customs', 'format', 'ten', 'format_date', 'autos'));
    }

    public function index_oned(Request $request)
    {
        if ($request->date) {
            $date = $request->date;
        } else {
            $date = Carbon::today();
        }

        $lucky_numbers = LuckyNumber::where('create_date', $date)->where('category_id', 4)->get();
        dd($lucky_numbers);
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d g:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $format = strtotime($date[1] . ' ' . $date[2]);
        $customs = CustomRecord::get();
        $autos = AutoRecord::where('record_date', $date)->get();
        $format_date = strtotime($date[0]);
        $ten = strtotime('10 minutes');

        return view('super_admin.lucky_number.index_1d', compact('lucky_numbers', 'customs', 'format', 'ten', 'format_date', 'autos'));
    }

    public function index_c2d(Request $request)
    {
        if ($request->date) {
            $date = $request->date;
        } else {
            $date = Carbon::today();
        }
        // CategoryId: 3, Crypto 2D
        $lucky_numbers = LuckyNumber::where('create_date', $date)->where('category_id', 3)->get();

        return view('super_admin.lucky_number.index_crypto_2d', compact('lucky_numbers'));
    }

    public function index_c1d(Request $request)
    {
        if ($request->date) {
            $date = $request->date;
        } else {
            $date = Carbon::today();
        }
        // CategoryId: 3, Crypto 2D
        $lucky_numbers = LuckyNumber::where('create_date', $date)->where('category_id', 5)->get();

        return view('super_admin.lucky_number.index_crypto_1d', compact('lucky_numbers'));
    }

    public function create_c1d()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d g:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $customs = Sectionc1d::where('is_open',1)->get();
        return view('super_admin.lucky_number.create_crypto_1d', compact('customs'));
    }

    public function approve_c1d($lucky_number)
    {
        $userbets = BettingCrypto1d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('user_bets')->get();
        foreach ($userbets as $bet) {
            if ($bet->bet_number == $lucky_number->lucky_number) {
                $bet->win = 1;
                $bet->save();
            } else {
                $bet->win = 2;
                $bet->save();
            }
        }

        $bettings = UserBetCrypto1d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('bettings')->get();
        foreach ($bettings as $b) {
            $check = $b->bettings->where('win', '=', 1)->first();
            if (!empty($check)) {
                $b->win = 1;
                $b->reward_amount = $check->amount * $check->odd;
                $b->save();

                //firebase win noti
                invokeAll::winLuckyNumberNoti($b->user_id, $lucky_number->lucky_number);
            } else {
                $b->win = 2;
                $b->save();
            }
        }
        return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d g:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $customs = Section::where('is_open',1)->get();

        return view('super_admin.lucky_number.create', compact('customs'));
    }

    public function create_c2d()
    {
        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $now = gmdate('Y-m-d g:i A', time() + $diffWithGMT);
        $date = explode(' ', $now);
        $customs = SectionCrypto2d::get();
        return view('super_admin.lucky_number.create_crypto_2d', compact('customs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LuckyNumberRequest $request)
    {
        $lucky_number = new LuckyNumber();
        $lucky_number->lucky_number = $request->lucky_number;
        $lucky_number->section = $request->section;
        $lucky_number->category_id = $request->category_id;
        $lucky_number->create_date = Carbon::parse($request->create_date);
        $lucky_number->save();
        return redirect('super_admin/lucky_number')->with('flash_message', 'Lucky Number Created');
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

    public function add(Request $request,$id){
        $date =  date('Y-m-d');
     $already = LuckyNumber::where('create_date', $date)->where('category_id',1)->where('section',$id)->first();
        if($already){
            return back()->with('error_message', $id .' already been taken');
        }
        $date = Carbon::now();
        $lucky_number = New LuckyNumber();
        $lucky_number->lucky_number = $request->two_num;
        $lucky_number->section = $id;
        $lucky_number->category_id = 1;
        $lucky_number->create_date = Carbon::parse($date);
        $lucky_number->save();
        return redirect('super_admin/lucky_number')->with('flash_message', $id .' Created');
    }

    public function approve_create_c2d($lucky_number)
    {
        $userbets = BettingCrypto2d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('user_bets_c2d')->get();
        foreach ($userbets as $bet) {
            if ($bet->bet_number == $lucky_number->lucky_number) {
                $bet->win = 1;
                $bet->save();
            } else {
                $bet->win = 2;
                $bet->save();
            }
        }

        $bettings = UserBetCrypto2d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('bettings_c2d')->get();
        foreach ($bettings as $b) {
            $check = $b->bettings_c2d->where('win', '=', 1)->first();
            if (!empty($check)) {
                $b->win = 1;
                $b->reward_amount = $check->amount * $check->odd;
                $b->save();

                //firebase win noti
                invokeAll::winLuckyNumberNoti($b->user_id, $lucky_number->lucky_number);
            } else {
                $b->win = 2;
                $b->save();
            }
        }
    }

    public function approve_create_c1d($lucky_number)
    {
        $userbets = BettingCrypto1d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('user_bets')->get();
        foreach ($userbets as $bet) {
            if ($bet->bet_number == $lucky_number->lucky_number) {
                $bet->win = 1;
                $bet->save();
            } else {
                $bet->win = 2;
                $bet->save();
            }
        }

        $bettings = UserBetCrypto1d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('bettings')->get();
        foreach ($bettings as $b) {
            $check = $b->bettings->where('win', '=', 1)->first();
            if (!empty($check)) {
                $b->win = 1;
                $b->reward_amount = $check->amount * $check->odd;
                $b->save();

                //firebase win noti
                invokeAll::winLuckyNumberNoti($b->user_id, $lucky_number->lucky_number);
            } else {
                $b->win = 2;
                $b->save();
            }
        }
    }


    public function add_crypto(Request $request,$id){
        $date =  date('Y-m-d');
     $already = LuckyNumber::where('create_date', $date)->where('category_id',3)->where('section',$id)->first();
        if($already){
            return back()->with('error_message', $id .' already been taken');
        }
        $date = Carbon::now();
        $lucky_number = New LuckyNumber();
        $lucky_number->lucky_number = $request->two_num;
        $lucky_number->section = $id;
        $lucky_number->category_id = 3;
        $lucky_number->create_date = Carbon::parse($date);
        $lucky_number->save();
        return redirect('super_admin/lucky_number_c2d')->with('flash_message', $id .' Created');
    }

    

    public function add_crypto_1d(Request $request,$id){
        $date =  date('Y-m-d');
        $already = LuckyNumber::where('create_date', $date)->where('category_id',5)->where('section',$id)->first();
        if($already){
            return back()->with('error_message', $id .' already been taken');
        }
        $date = Carbon::now();
        $lucky_number = New LuckyNumber();
        $lucky_number->lucky_number = $request->two_num;
        $lucky_number->section = $id;
        $lucky_number->category_id = 5;
        $lucky_number->create_date = Carbon::parse($date);
        $lucky_number->save();
        return redirect('super_admin/lucky_number_c1d')->with('flash_message', $id .' Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sections = Section::all();
        $categories = Category::all();
        $lucky_number = LuckyNumber::findOrFail($id);
        return view('super_admin.lucky_number.edit', compact('sections', 'categories', 'lucky_number'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LuckyNumberRequest $request, $id)
    {
        $lucky_number = LuckyNumber::findOrFail($id);
        $lucky_number->lucky_number = $request->lucky_number;
        $lucky_number->section = $request->section;
        $lucky_number->category_id = $request->category_id;
        $lucky_number->create_date = Carbon::parse($request->create_date);
        $lucky_number->update();
        return redirect('super_admin/lucky_number')->with('flash_message', 'Lucky Number Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LuckyNumber::find($id)->delete();
        return redirect('super_admin/lucky_number')->with('flash_message', 'Lucky Number Deleted');
    }

    public function approve(Request $request, $id)
    {

            $lucky = LuckyNumber::findOrFail($id);
            $lucky->approve = 1;
            $lucky->update();

            $userbets = Betting::where('date', $lucky->create_date)->where('section', $lucky->section)->with('user_bets')->get();
            foreach ($userbets as $bet) {
                if ($bet->bet_number == $lucky->lucky_number) {
                    $bet->win = 1;
                    $bet->save();
                } else {
                    $bet->win = 2;
                    $bet->save();
                }
            }

            $bettings = UserBet::where('date', $lucky->create_date)->where('section', $lucky->section)->with('bettings')->get();
            foreach ($bettings as $b) {
                $check = $b->bettings->where('win', '=', 1)->first();
                if (!empty($check)) {
                    $b->win = 1;
                    $b->reward_amount = $check->amount * $check->odd;
                    $b->save();

                    //firebase win noti
                    invokeAll::winLuckyNumberNoti($b->user_id, $lucky->lucky_number);
                } else {
                    $b->win = 2;
                    $b->save();
                }
            }
        

        return redirect('super_admin/lucky_number')->with('flash_message', 'Lucky Number Update');
    }

    public function approve_1d(Request $request, $id)
    {

        $lucky = LuckyNumber::findOrFail($id);

            $lucky->approve = 1;
            $lucky->update();

            $userbets = Betting1d::where('date', $lucky->create_date)->where('section', $lucky->section)->with('user_bets')->get();
            foreach ($userbets as $bet) {
                if ($bet->bet_number == $lucky->lucky_number) {
                    $bet->win = 1;
                    $bet->save();
                } else {
                    $bet->win = 2;
                    $bet->save();
                }
            }

            $bettings = UserBet1d::where('date', $lucky->create_date)->where('section', $lucky->section)->with('bettings')->get();
            foreach ($bettings as $b) {
                $check = $b->bettings->where('win', '=', 1)->first();
                if (!empty($check)) {
                    $b->win = 1;
                    $b->reward_amount = $check->amount * $check->odd;
                    $b->save();

                    //firebase win noti
                    invokeAll::winLuckyNumberNoti($b->user_id, $lucky->lucky_number);
                } else {
                    $b->win = 2;
                    $b->save();
                }
            }
        

        return redirect('super_admin/lucky_number')->with('flash_message', 'Lucky Number Update');
    }

    public function approve_c2d($lucky_number)
    {
        $userbets = BettingCrypto2d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('user_bets_c2d')->get();
        foreach ($userbets as $bet) {
            if ($bet->bet_number == $lucky_number->lucky_number) {
                $bet->win = 1;
                $bet->save();
            } else {
                $bet->win = 2;
                $bet->save();
            }
        }

        $bettings = UserBetCrypto2d::where('date', $lucky_number->create_date)->where('section', $lucky_number->section)->with('bettings_c2d')->get();
        foreach ($bettings as $b) {
            $check = $b->bettings_c2d->where('win', '=', 1)->first();
            if (!empty($check)) {
                $b->win = 1;
                $b->reward_amount = $check->amount * $check->odd;
                $b->save();

                //firebase win noti
                invokeAll::winLuckyNumberNoti($b->user_id, $lucky_number->lucky_number);
            } else {
                $b->win = 2;
                $b->save();
            }
        }
        return;
    }

    public function index_3d(Request $request)
    {
        if ($request->month) {
            $month = $request->month;
        } else {
            $month = date('m');
        }

        if ($request->year) {
            $year = $request->year;
        } else {
            $year = date('Y');
        }

        $months = invoke3D::months();
        $cur_month = date('m');
        $years = invoke3D::years();
        $cur_year = date('Y');

        $lucky_numbers = LuckyNumber::whereMonth('create_date', $month)->whereYear('create_date', $year)->where('category_id', 2)->get();
        return view('super_admin.lucky_number.index_3d', compact('lucky_numbers', 'months', 'cur_month', 'years', 'cur_year'));
    }

    public function approve_3d(Request $request, $id)
    {
        $request->validate([
            'lucky_number' => 'required',
        ]);

        $lucky = LuckyNumber::findOrFail($id);
        $lucky->lucky_number = $request->lucky_number;
        $lucky->update();

        if ($request->approve) {

            $lucky->approve = 1;
            $lucky->update();

            $userbets = Betting3d::where('bet_date', $lucky->create_date)->with('user_bets_3d')->get();
            foreach ($userbets as $bet) {

                //tot
                $tot = invoke3D::tot3DFunUpDown($bet->bet_number, $lucky->lucky_number);

                if ($bet->bet_number == $lucky->lucky_number) {
                    $bet->win = 1;
                    $bet->save();
                } else {
                    if ($tot == 'yes') {
                        $bet->win = 1;
                        $bet->tot = 1;
                        $bet->save();
                    } else {
                        $bet->win = 2;
                        $bet->save();
                    }
                }
            }

            $bettings = UserBet3d::where('bet_date', $lucky->create_date)->with('bettings_3d')->get();
            foreach ($bettings as $b) {

                //reward amount 3d
                $bets = $b->bettings_3d;
                foreach ($bets as $bet) {
                    if ($bet->win == 1) {
                        if ($bet->tot == 1) {
                            $b->reward_amount += $bet->amount * $bet->tot_odd;
                        } else {
                            $b->reward_amount += $bet->amount * $bet->odd;
                        }
                    }
                }

                $check = $b->bettings_3d->where('win', '=', 1)->first();
                if (!empty($check)) {

                    $b->win = 1;
                    $b->save();

                    //firebase win noti
                    invokeAll::winLuckyNumberNoti($b->user_id, $lucky->lucky_number);
                } else {
                    $b->win = 2;
                    $b->save();
                }
            }
        }

        return redirect('super_admin/lucky_number_3d')->with('flash_message', 'Lucky Number Update');
    }
}
