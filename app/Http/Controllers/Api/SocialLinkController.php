<?php

namespace App\Http\Controllers\Api;

use App\Models\SocialLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return response()->json([
            'facebook' => $facebook->value,
            'viber' => $viber->value,
            'messenger' => $messenger->value,
            'instagram' => $instagram->value,
            'play_store' => $play_store->value,
            'qr' => $qr->value,
            'media_fire' => $media_fire->value
        ]);
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
        return response()->json([
            'facebook' => $facebook->value,
            'viber' => $viber->value,
            'messenger' => $messenger->value,
            'instagram' => $instagram->value,
            'play_store' => $play_store->value,
            'qr' => $qr->value,
            'media_fire' => $media_fire->value
        ]);
    }
}
