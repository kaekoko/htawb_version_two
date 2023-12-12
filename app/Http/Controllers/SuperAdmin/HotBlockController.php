<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Section;
use App\Models\Section1d;
use App\Models\Section3d;
use App\Models\Sectionc1d;
use Illuminate\Http\Request;
use App\Models\NumberSetting;
use PhpParser\Node\Expr\New_;
use App\Models\NumberSetting1d;
use App\Models\NumberSetting3d;
use App\Models\SectionCrypto2d;
use App\Models\NumberSettingc1d;
use App\Http\Controllers\Controller;
use App\Models\NumberSettingCrypto2d;

class HotBlockController extends Controller
{
    public function index()
    {
        $date =  date('Y-m-d');

        $find_num = NumberSetting::whereDate('created_at', $date)->get();
        if (count($find_num) <= 0) {
            $hot_times = Section::where("is_open", 1)->get();
            foreach ($hot_times as $time) {
                $hot = new NumberSetting();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = Section::where("is_open", 1)->get();
            foreach ($block_times as $time) {
                $block = new NumberSetting();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
        $hots  = NumberSetting::where('type', 'hot')->whereDate('created_at', $date)->get();
        $blocks  = NumberSetting::where('type', 'block')->whereDate('created_at', $date)->get();
        return view('super_admin.hotblock.index', compact('hots', 'blocks'));
    }

    public function index_1d()
    {
        $date =  date('Y-m-d');

        $find_num = NumberSetting1d::whereDate('created_at', $date)->get();
        if (count($find_num) <= 0) {
            $hot_times = Section1d::where("is_open", 1)->get();
            foreach ($hot_times as $time) {
                $hot = new NumberSetting1d();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = Section1d::where("is_open", 1)->get();
            foreach ($block_times as $time) {
                $block = new NumberSetting1d();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
        $hots  = NumberSetting1d::where('type', 'hot')->whereDate('created_at', $date)->get();
        $blocks  = NumberSetting1d::where('type', 'block')->whereDate('created_at', $date)->get();
        return view('super_admin.hotblock.index_1d', compact('hots', 'blocks'));
    }

    public function index_c2d()
    {
        $date =  date('Y-m-d');

        $find_num = NumberSettingCrypto2d::whereDate('created_at', $date)->get();
        if (count($find_num) == 0) {
            $hot_times = SectionCrypto2d::get();
            foreach ($hot_times as $time) {
                $hot = new NumberSettingCrypto2d();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = SectionCrypto2d::get();
            foreach ($block_times as $time) {
                $block = new NumberSettingCrypto2d();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
        $hots  = NumberSettingCrypto2d::where('type', 'hot')->whereDate('created_at', $date)->get();
        $blocks  = NumberSettingCrypto2d::where('type', 'block')->whereDate('created_at', $date)->get();

        return view('super_admin.hotblock.index_crypto_2d', compact('hots', 'blocks'));
    }

    public function index_c1d()
    {
        $date =  date('Y-m-d');

        $find_num = NumberSettingc1d::whereDate('created_at', $date)->get();
        if (count($find_num) == 0) {
            $hot_times = Sectionc1d::where("is_open", 1)->get();
            foreach ($hot_times as $time) {
                $hot = new NumberSettingc1d();
                $hot->section = date("h:i A", strtotime($time->time_section));
                $hot->type = 'hot';
                $hot->hot_number = '-';
                $hot->hot_amount = NULL;
                $hot->save();
            }
            $block_times = Sectionc1d::where("is_open", 1)->get();
            foreach ($block_times as $time) {
                $block = new NumberSettingc1d();
                $block->section = date("h:i A", strtotime($time->time_section));
                $block->type = 'block';
                $block->block_number = NULL;
                $block->save();
            }
        }
        $hots  = NumberSettingc1d::where('type', 'hot')->whereDate('created_at', $date)->get();
        $blocks  = NumberSettingc1d::where('type', 'block')->whereDate('created_at', $date)->get();

        return view('super_admin.hotblock.index_crypto_1d', compact('hots', 'blocks'));
    }

    public function hotblock_c1d(Request $request, NumberSettingc1d $hotblock)
    { // For Crypto 2D
        if ($request->type === 'hot') {
            $hotblock->hot_number = $request->hot_number;
            $hotblock->hot_amount = $request->hot_amount;
            $hotblock->save();
        } else if ($request->type === "block") {
            $hotblock->block_number = $request->block_number;
            $hotblock->save();
        }
        return response([
            "message" => 'success'
        ]);
    }

    public function hotblock(Request $request, $id)
    {
        $find = NumberSetting::findOrFail($id);
        if ($request->type === 'hot') {
            $find->hot_number = $request->hot_number;
            $find->hot_amount = $request->hot_amount;
            $find->save();
        } else {
            $find->block_number = $request->block_number;
            $find->save();
        }
        return response([
            "message" => 'success'
        ]);
    }

    public function hotblock_1d(Request $request, $id)
    {
        $find = NumberSetting1d::findOrFail($id);
        if ($request->type === 'hot') {
            $find->hot_number = $request->hot_number;
            $find->hot_amount = $request->hot_amount;
            $find->save();
        } else {
            $find->block_number = $request->block_number;
            $find->save();
        }
        return response([
            "message" => 'success'
        ]);
    }

    public function hotblock_c2d(Request $request, NumberSettingCrypto2d $hotblock)
    { // For Crypto 2D
        if ($request->type === 'hot') {
            $hotblock->hot_number = $request->hot_number;
            $hotblock->hot_amount = $request->hot_amount;
            $hotblock->save();
        } else if ($request->type === "block") {
            $hotblock->block_number = $request->block_number;
            $hotblock->save();
        }
        return response([
            "message" => 'success'
        ]);
    }

    

    public function index_3d()
    {
        $hots = NumberSetting3d::where('type', 'hot')->get();
        $blocks = NumberSetting3d::where('type', 'block')->get();
        return view('super_admin.hotblock.index_3d', compact('hots', 'blocks'));
    }

    public function create_hot_3d(Request $request)
    {
        $request->validate([
            'hot_number' => 'required',
            'hot_amount' => 'required',
        ]);

        $hots = NumberSetting3d::where('type', 'hot')->get(['hot_number', 'hot_amount']);

        $check_nums = explode(',', $request->hot_number);
        foreach ($check_nums as $hot_number) {
            $isHot = 'no';
            foreach ($hots as $hot) {
                $isHot = in_array($hot_number, explode(',', $hot->hot_number)) ? 'yes' : 'no';
                if ($isHot == 'yes') {
                    return redirect('super_admin/hotblock_3d')->with('error_message', 'Hot Number Alreay Exit!');
                }
            }
        }
        if ($isHot == 'no') {
            $hot = new NumberSetting3d();
            $hot->hot_number = $request->hot_number;
            $hot->hot_amount = $request->hot_amount;
            $hot->type = 'hot';
            $hot->save();

            return redirect('super_admin/hotblock_3d')->with('flash_message', 'Add Hot Number in 3D');
        }
    }

    public function edit_hot_3d(Request $request, $id)
    {
        $hots = NumberSetting3d::where('type', 'hot')->where('id', '!=', $id)->get(['hot_number', 'hot_amount']);

        $check_nums = explode(',', $request->hot_number);
        foreach ($check_nums as $hot_number) {
            $isHot = 'no';
            foreach ($hots as $hot) {
                $isHot = in_array($hot_number, explode(',', $hot->hot_number)) ? 'yes' : 'no';
                if ($isHot == 'yes') {
                    return redirect('super_admin/hotblock_3d')->with('error_message', 'Hot Number Alreay Exit!');
                }
            }
        }
        if ($isHot == 'no') {
            $hot = NumberSetting3d::findOrFail($id);
            $hot->hot_number = $request->hot_number;
            $hot->hot_amount = $request->hot_amount;
            $hot->update();
            return redirect('super_admin/hotblock_3d')->with('flash_message', 'Edit Hot Number in 3D');
        }
    }

    public function create_block_3d(Request $request)
    {
        $request->validate([
            'block_number' => 'required',
        ]);

        $blocks = NumberSetting3d::where('type', 'block')->get('block_number');

        $check_nums = explode(',', $request->block_number);
        foreach ($check_nums as $block_number) {
            $isBlock = 'no';
            foreach ($blocks as $block) {
                $isBlock = in_array($block_number, explode(',', $block->block_number)) ? 'yes' : 'no';
                if ($isBlock == 'yes') {
                    return redirect('super_admin/hotblock_3d')->with('error_message', 'Block Number Alreay Exit!');
                }
            }
        }
        if ($isBlock == 'no') {
            $hot = new NumberSetting3d();
            $hot->block_number = $request->block_number;
            $hot->type = 'block';
            $hot->save();

            return redirect('super_admin/hotblock_3d')->with('flash_message', 'Add Block Number in 3D');
        }
    }

    public function edit_block_3d(Request $request, $id)
    {
        $blocks = NumberSetting3d::where('type', 'block')->where('id', '!=', $id)->get('block_number');

        $check_nums = explode(',', $request->block_number);
        foreach ($check_nums as $block_number) {
            $isBlock = 'no';
            foreach ($blocks as $block) {
                $isBlock = in_array($block_number, explode(',', $block->block_number)) ? 'yes' : 'no';
                if ($isBlock == 'yes') {
                    return redirect('super_admin/hotblock_3d')->with('error_message', 'Block Number Alreay Exit!');
                }
            }
        }
        $hot = NumberSetting3d::findOrFail($id);
        $hot->block_number = $request->block_number;
        $hot->update();
        return redirect('super_admin/hotblock_3d')->with('flash_message', 'Edit Block Number in 3D');
    }

    public function delete_hot_block($id)
    {
        $hot = NumberSetting3d::findOrFail($id);
        $hot->delete();
        return redirect('super_admin/hotblock_3d')->with('flash_message', 'Delete Hot Number in 3D');
    }
}
