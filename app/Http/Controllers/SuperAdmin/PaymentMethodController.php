<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\PaymentAccountNumber;
use Illuminate\Support\Facades\File;
use App\Models\PaymentNewAccountNumber;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PaymentMethodRequest;
use App\Http\Controllers\SuperAdmin\NewPaymentMethodController;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_methods = PaymentMethod::all();
        return view('super_admin.payment_method.index', compact('payment_methods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.payment_method.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentMethodRequest $request)
    {
        $payment_method = new PaymentMethod();
        $payment_method->name = $request->name;
        $payment_method->type = $request->type;
        $payment_method->status = '0';
        $payment_method->cash_in_status = '0';
        $payment_method->cash_out_status = '0';
        // only for mobile-topup
        if (isset($request->percentage)) {
            $payment_method->percentage = $request->percentage;
        }
        // only for foreign-banking
        if (isset($request->exchange_rate)) {
            $payment_method->exchange_rate = $request->exchange_rate;
        }
        if ($request->hasFile('logo')) {
            $image = $request->logo;
            $image_file = $image->getClientOriginalName();
            Storage::disk('public')->put('payment_method_logo/' . $image_file, File::get($image));
            $payment_method->logo = 'storage/payment_method_logo/' . $image_file;
        }
        $payment_method->save();

        if ($payment_method->type != "mobile-topup") {
            foreach ($request->account_number as $key => $value) {
                $payment_account_number = new PaymentAccountNumber();
                $payment_account_number->payment_id = $payment_method->id;
                $payment_account_number->account_number = $request->account_number[$key];
                $payment_account_number->account_name = $request->account_name[$key];
                $payment_account_number->save();
            }
        }

        return redirect('super_admin/payment_method')->with('flash_message', 'Payment Method Created');
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
        $payment_method = PaymentMethod::findOrFail($id);
        return view('super_admin.payment_method.edit', compact('payment_method'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethodRequest $request, $id)
    {
        $payment_method = PaymentMethod::findOrFail($id);
        $payment_method->name = $request->name;
        $payment_method->type = $request->type;
        // only for mobile-topup
        if (isset($request->percentage)) {
            $payment_method->percentage = $request->percentage;
        }
         // only for foreign-banking
         if (isset($request->exchange_rate)) {
            $payment_method->exchange_rate = $request->exchange_rate;
        }
        if ($request->hasFile('logo')) {
            if ($request->file('logo')) {
                $image = $request->logo;
                $image_file = $image->getClientOriginalName();
                Storage::disk('public')->put('payment_method_logo/' . $image_file, File::get($image));
                $payment_method->logo = 'storage/payment_method_logo/' . $image_file;
            } else {
                $payment_method->logo = $payment_method->logo;
            }
        }
        $payment_method->update();

        if ($request->edit_account_number[0] ?? false) {
            foreach ($request->edit_account_number as $key => $value) {
                $payment_account_number_edit = PaymentAccountNumber::findOrFail($request->edit_id[$key]);
                $payment_account_number_edit->account_number = $request->edit_account_number[$key];
                $payment_account_number_edit->account_name = $request->edit_account_name[$key];
                $payment_account_number_edit->update();
            }
        }

        if ($request->account_number[0] ?? false) {
            foreach ($request->account_number as $key => $value) {
                $payment_account_number = new PaymentAccountNumber();
                $payment_account_number->payment_id = $payment_method->id;
                $payment_account_number->account_number = $request->account_number[$key];
                $payment_account_number->account_name = $request->account_name[$key];
                $payment_account_number->save();
            }
        }

        return redirect('super_admin/payment_method')->with('flash_message', 'Payment Method Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentMethod::find($id)->delete();
        return redirect('super_admin/payment_method')->with('flash_message', 'Payment Method Deleted');
    }

    public function updateCashinStatus(Request $request)
    {
        $payment_method = PaymentMethod::findorFail($request->id);
        $payment_method->cash_in_status = $request->status;
        $payment_method->update();

        return response()->json([
            'name' => $payment_method->name,
            'status' => $request->status,
            'message' => 'Cashin Status Change',
        ]);
    }

    public function updateCashoutStatus(Request $request)
    {
        $payment_method = PaymentMethod::findorFail($request->id);
        $payment_method->cash_out_status = $request->status;
        $payment_method->update();

        return response()->json([
            'name' => $payment_method->name,
            'status' => $request->status,
            'message' => 'Cashout Status Change',
        ]);
    }

    public function updateStatus(Request $request)
    {
        $payment_method = PaymentMethod::findorFail($request->id);
        $payment_method->status = $request->status;
        $payment_method->update();

        return response()->json([
            'name' => $payment_method->name,
            'status' => $request->status,
            'message' => 'Status Change',
        ]);
    }

    public function paymentAccountDelete($id)
    {
        $payment_account_number = PaymentAccountNumber::findOrFail($id);
        $payment_account_number->delete();

        return back()->with('flash_message', 'Deleted Success!');
    }

    public function paymentAccountDeleteNew($id)
    {
        $payment_account_number = PaymentNewAccountNumber::findOrFail($id);
        $payment_account_number->delete();

        return back()->with('flash_message', 'Deleted Success!');
    }

    
}
