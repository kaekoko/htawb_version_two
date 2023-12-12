<?php

namespace App\Http\Controllers\SuperAdmin;

use Carbon\Carbon;
use App\Models\Section;
use App\Invoker\invoke3D;
use App\Models\Section1d;
use App\Models\Section3d;
use App\Models\CustomRecord;
use Illuminate\Http\Request;
use App\Models\NumberSetting;
use App\Models\SectionCrypto2d;
use Database\Seeders\NumberSeeder;
use App\Http\Controllers\Controller;
use App\Http\Requests\SectionRequest;
use App\Http\Requests\Section3dRequest;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time_sections = Section::all();
        $sections_3d = Section3d::all();
        $sections_1d = Section1d::all();
        $crypto_time_sections = SectionCrypto2d::all();
        return view('super_admin.section.index', compact('crypto_time_sections', 'time_sections', 'sections_3d','sections_1d'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('super_admin.section.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionRequest $request)
    {
        $time_section = new Section();
        $time_section->time_section = Carbon::parse($request->time_section)->format("H:i:s");
        $time_section->open_time = Carbon::parse($request->open_time)->format("H:i:s");
        $time_section->close_time = Carbon::parse($request->close_time)->format("H:i:s");
        $time_section->save();

        $hot = new NumberSetting();
        $hot->hot_number = '-';
        $hot->hot_amount = NULL;
        $hot->type = 'hot';
        $hot->section = date("h:i A", strtotime($time_section->time_section));
        $hot->save();

        $block = new NumberSetting();
        $block->block_number = NULL;
        $block->type = 'block';
        $block->section = date("h:i A", strtotime($time_section->time_section));
        $block->save();

        $diffWithGMT = 6 * 60 * 60 + 30 * 60;
        $date = gmdate('Y-m-d', time() + $diffWithGMT);
        $custom = new CustomRecord();
        $custom->record_date = $date;
        $custom->record_time = date("h:i A", strtotime($time_section->time_section));
        $custom->twod_number = '-';
        $custom->save();

        return redirect('super_admin/section')->with('flash_message', 'Time Section Created');
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
        $time_section = Section::findOrFail($id);
        return view('super_admin.section.edit', compact('time_section'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SectionRequest $request, $id)
    {
        $time_section = Section::findOrFail($id);
        $time_section->time_section = Carbon::parse($request->time_section)->format("H:i:s");
        $time_section->open_time = Carbon::parse($request->open_time)->format("H:i:s");
        $time_section->close_time = Carbon::parse($request->close_time)->format("H:i:s");
        $time_section->update();
        return redirect('super_admin/section')->with('flash_message', 'Time Section Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Section::find($id)->delete();

        return redirect('super_admin/section')->with('flash_message', 'Time Section Deleted');
    }

    public function create_3d()
    {
        return view('super_admin.section.create_3d');
    }

    public function store_3d(Section3dRequest $request)
    {
        $section = new Section3d();
        $section->time = Carbon::parse($request->time)->format("H:i:s");
        $section->close_time = Carbon::parse($request->close_time)->format("H:i:s");
        $section->date = Carbon::parse($request->date);
        $section->save();
        return redirect('super_admin/section')->with('flash_message', '3D Section Created');
    }

    public function edit_3d($id)
    {
        $section_3d = Section3d::findOrFail($id);
        $option_1 = [
            "28" => "28",
            "29" => "29",
            "30" => "30",
            "31" => "31",
            "01" => "01",
            "02" => "02",
        ];
        $option_2 = [
            "14" => "14",
            "15" => "15",
            "16" => "16",
            "17" => "17"
        ];
        return view('super_admin.section.edit_3d', compact('section_3d', 'option_1', 'option_2'));
    }

    public function update_3d(Section3dRequest $request, $id)
    {

        $section = Section3d::findOrFail($id);

        //betting in bet date change
        $betting = invoke3D::editSectionChangeBetDate3D($request->date, $section->date, $id);

        $section->time = Carbon::parse($request->time)->format("H:i:s");
        $section->close_time = Carbon::parse($request->close_time)->format("H:i:s");
        $section->date = $request->date;
        $section->update();

        return redirect('super_admin/section')->with('flash_message', '3D Section Update');
    }

    public function destroy_3d($id)
    {
        Section3d::find($id)->delete();
        return redirect('super_admin/section')->with('flash_message', '3D Section Deleted');
    }

    public function openOrNotSection2D(Request $request)
    {
        $section = Section::where('id', $request->id)->first();
        $section->is_open = $request->is_open;
        $res = $section->save();
        if ($res) {
            $number_settings = NumberSetting::pluck("id");
            if (count($number_settings) > 0) {
                NumberSetting::whereIn("id", $number_settings)->delete();
            }
            $custom_records = CustomRecord::pluck("id"); // 2D
            if (count($custom_records) > 0) {
                CustomRecord::whereIn("id", $custom_records)->delete();
            }
            if ($request->is_open) {
                return response()->json([
                    'message' => '2D Section: ' . date("h:i A", strtotime($section->time_section)) . ' Is Opened Successful.'
                ], 200);
            } else {
                return response()->json([
                    'message' => '2D Section: ' . date("h:i A", strtotime($section->time_section)) . ' Is Closed Successful.'
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Please try again.'
            ], 400);
        }
    }
}
