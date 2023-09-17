<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Link;
use App\Models\LinkDetail;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);

        $link = Link::create([
            'user_id' => $user->id,
        ]);

        $socialMedia = [
            ['url' => 'https://www.facebook.com', 'link' => 'https://www.facebook.com/myprofile', 'type' => 'facebook', 'share_able' => true],
            ['url' => 'https://www.linkedin.com', 'link' => 'https://www.linkedin.com/myprofile', 'type' => 'linkedin', 'share_able' => true],
            ['url' => 'https://www.pinterest.com', 'link' => 'https://www.pinterest.com/myprofile', 'type' => 'pinterest', 'share_able' => true],
            ['url' => 'https://www.instagram.com', 'link' => 'https://www.instagram.com/myprofile', 'type' => 'instagram', 'share_able' => true],
            ['url' => 'https://www.twitter.com', 'link' => 'https://www.twitter.com/myprofile', 'type' => 'twitter', 'share_able' => true],
            ['url' => 'https://www.youtube.com', 'link' => 'https://www.youtube.com/mychannel', 'type' => 'youtube', 'share_able' => true],
        ];

        foreach ($socialMedia as $media) {
            LinkDetail::create([
                'link_id' => $link->id,
                'url' => $media['url'],
                'link' => $media['link'],
                'type' => $media['type'],
                'share_able' => $media['share_able'],
            ]);
        }
    }
}
