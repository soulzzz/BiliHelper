<?php

/*!
 * metowolf BilibiliHelper
 * https://i-meto.com/
 * Version 18.04.25 (0.7.3)
 *
 * Copyright 2018, metowolf
 * Released under the MIT license
 */

namespace lkeme\BiliHelper;

use lkeme\BiliHelper\Curl;
use lkeme\BiliHelper\Sign;
use lkeme\BiliHelper\Log;

class Heart
{
    public static $lock = 0;

    public static function run()
    {
        if (self::$lock > time()) {
            return;
        }

        self::pc();
        self::mobile();

        self::$lock = time() + 300;
    }

    protected static function pc()
    {
        $payload = [
            'room_id' => getenv('ROOM_ID'),
        ];
        $data = Curl::post('https://api.live.bilibili.com/User/userOnlineHeart', Sign::api($payload));
        $data = json_decode($data, true);

        if (isset($data['code']) && $data['code']) {
            Log::warning('WEB 端的直播间心跳停止惹～', ['msg' => $data['message']]);
        } else {
            Log::info('WEB 心跳正常');
        }
    }

    protected static function mobile()
    {
        $payload = [
            'room_id' => getenv('ROOM_ID'),
        ];
        $data = Curl::post('https://api.live.bilibili.com/mobile/userOnlineHeart', Sign::api($payload));
        $data = json_decode($data, true);

        if (isset($data['code']) && $data['code']) {
            Log::warning('APP 端的直播间心跳停止惹～', ['msg' => $data['message']]);
        } else {
            Log::info('APP 心跳正常');
        }
    }
}
