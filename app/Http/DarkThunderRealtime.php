<?php

namespace App\Http;

class  DarkThunderRealtime{
    private static $url = "http://localhost:7000/dispatch";
    public static function dispatchEvent($event,$data) {
        $request = curl_init(self::$url);
        curl_setopt_array($request,[
            CURLOPT_POST=>true,
            CURLOPT_POSTFIELDS=>[
                "event"=>$event,
                "data"=>$data
            ],
            CURLOPT_RETURNTRANSFER=>true
        ]);
        return curl_exec($request);
    }
}
