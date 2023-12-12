<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\SocialLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SocialLinkController extends Controller
{
    public function index()
    {
        $facebook = SocialLink::where('key', 'facebook')->first();
        $viber = SocialLink::where('key', 'viber')->first();
        $messenger = SocialLink::where('key', 'messenger')->first();
        $instagram = SocialLink::where('key', 'instagram')->first();
        $play_store = SocialLink::where('key', 'play_store')->first();
        $media_fire = SocialLink::where('key', 'media_fire')->first();
        $qr = SocialLink::where('key', 'qr')->first();
        return view('super_admin.social_link.index',compact('facebook','viber','messenger','instagram','play_store','qr', 'media_fire'));
    }

    public function update(Request $request)
    {
        if($request->facebook){
            $social = SocialLink::where('key', 'facebook')->first();
            $social->value = $request->facebook;
            $social->update();
            return redirect()->back()->with('flash_message', 'Facebook Updated');
        }
        if($request->viber){
            $social = SocialLink::where('key', 'viber')->first();
            $social->value = $request->viber;
            $social->update();
            return redirect()->back()->with('flash_message', 'Viber Updated');
        }
        if($request->messenger){
            $social = SocialLink::where('key', 'messenger')->first();
            $social->value = $request->messenger;
            $social->update();
            return redirect()->back()->with('flash_message', 'Messenger Updated');
        }
        if($request->instagram){
            $social = SocialLink::where('key', 'instagram')->first();
            $social->value = $request->instagram;
            $social->update();
            return redirect()->back()->with('flash_message', 'Instagram Updated');
        }
        if($request->play_store){
            $social = SocialLink::where('key', 'play_store')->first();
            $social->value = $request->play_store;
            $social->update();
            return redirect()->back()->with('flash_message', 'Play Store Updated');
        }
        if($request->media_fire){
            $social = SocialLink::where('key', 'media_fire')->first();
            $social->value = $request->media_fire;
            $social->update();
            return redirect()->back()->with('flash_message', 'MediaFire Updated');
        }
        if($request->hasFile('qr')){
            $social = SocialLink::where('key', 'qr')->first();
            Storage::delete($social->value);
            $social->value =  $request->file('qr')->store('super_admin');
            $social->update();
            return redirect()->back()->with('flash_message', 'Qr Image Updated');
        }
    }

    public function qr_image_delete()
    {
        $social = SocialLink::where('key', 'qr')->first();
        Storage::delete($social->value);
        $social->value =  '';
        $social->update();
        return redirect()->back()->with('flash_message', 'Qr Image Removed');
    }



    public function index_two()
    {
        $facebook = SocialLink::where('key', 'i_facebook')->first();
        $viber = SocialLink::where('key', 'i_viber')->first();
        $messenger = SocialLink::where('key', 'i_messenger')->first();
        $instagram = SocialLink::where('key', 'i_telegram')->first();
        $play_store = SocialLink::where('key', 'i_play_store')->first();
        $media_fire = SocialLink::where('key', 'i_media_fire')->first();
        $qr = SocialLink::where('key', 'i_qr')->first();
        return view('super_admin.game.social',compact('facebook','viber','messenger','instagram','play_store','qr', 'media_fire'));
    }

    public function update_two(Request $request)
    {
        if($request->facebook){
            $social = SocialLink::where('key', 'i_facebook')->first();
            $social->value = $request->facebook;
            $social->update();
            return redirect()->back()->with('flash_message', 'Facebook Updated');
        }
        if($request->viber){
            $social = SocialLink::where('key', 'i_viber')->first();
            $social->value = $request->viber;
            $social->update();
            return redirect()->back()->with('flash_message', 'Viber Updated');
        }
        if($request->messenger){
            $social = SocialLink::where('key', 'i_messenger')->first();
            $social->value = $request->messenger;
            $social->update();
            return redirect()->back()->with('flash_message', 'Messenger Updated');
        }
        if($request->instagram){
            $social = SocialLink::where('key', 'i_telegram')->first();
            $social->value = $request->instagram;
            $social->update();
            return redirect()->back()->with('flash_message', 'Telegram Updated');
        }
        if($request->play_store){
            $social = SocialLink::where('key', 'i_play_store')->first();
            $social->value = $request->play_store;
            $social->update();
            return redirect()->back()->with('flash_message', 'Play Store Updated');
        }
        if($request->media_fire){
            $social = SocialLink::where('key', 'i_media_fire')->first();
            $social->value = $request->media_fire;
            $social->update();
            return redirect()->back()->with('flash_message', 'MediaFire Updated');
        }
        if($request->hasFile('qr')){
            $social = SocialLink::where('key', 'i_qr')->first();
            Storage::delete($social->value);
            $social->value =  $request->file('qr')->store('super_admin');
            $social->update();
            return redirect()->back()->with('flash_message', 'Qr Image Updated');
        }
    }

    public function qr_image_delete_two()
    {
        $social = SocialLink::where('key', 'i_qr')->first();
        Storage::delete($social->value);
        $social->value =  '';
        $social->update();
        return redirect()->back()->with('flash_message', 'Qr Image Removed');
    }
}
