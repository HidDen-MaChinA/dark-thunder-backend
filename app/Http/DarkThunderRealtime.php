<?php

namespace App\Http;

use Illuminate\Support\Facades\Http;

class  DarkThunderRealtime{
    private static $url = "http://localhost:7000/dispatch";
    public static function dispatchEvent($event,$data) {
        Http::post(self::$url, $data);
    }
}
