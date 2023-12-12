<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use App\Models\SectionCrypto2d;
use Carbon\Carbon;

class SectionCrypto2dController extends Controller
{

    public function edit(SectionCrypto2d $time_section)
    {
        return view('super_admin.section_crypto_2d.edit', compact('time_section'));
    }

    public function update(SectionRequest $request, SectionCrypto2d $time_section)
    {
        $time_section->time_section = Carbon::parse($request->time_section)->format("H:i:s");
        $time_section->open_time = Carbon::parse($request->open_time)->format("H:i:s");
        $time_section->close_time = Carbon::parse($request->close_time)->format("H:i:s");
        $time_section->update();
        return redirect('super_admin/section')->with('flash_message', 'Time Section Update');
    }
}
