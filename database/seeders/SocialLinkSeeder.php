<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    protected $social_links = [
        [
            "key" => "facebook",
            "value" => ""
        ],
        [
            "key" => "viber",
            "value" => ""
        ],
        [
            "key" => "messenger",
            "value" => ""
        ],
        [
            "key" => "instagram",
            "value" => ""
        ],
        [
            "key" => "play_store",
            "value" => ""
        ],
        [
            "key" => "media_fire",
            "value" => ""
        ],
        [
            "key" => "qr",
            "value" => ""
        ],


        [
            "key" => "i_facebook",
            "value" => ""
        ],
        [
            "key" => "i_viber",
            "value" => ""
        ],
        [
            "key" => "i_messenger",
            "value" => ""
        ],
        [
            "key" => "i_telegram",
            "value" => ""
        ],
        [
            "key" => "i_play_store",
            "value" => ""
        ],
        [
            "key" => "i_media_fire",
            "value" => ""
        ],
        [
            "key" => "i_qr",
            "value" => ""
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialLink::truncate();
        foreach ($this->social_links as $social_link) {
            SocialLink::create($social_link);
        }
    }
}
