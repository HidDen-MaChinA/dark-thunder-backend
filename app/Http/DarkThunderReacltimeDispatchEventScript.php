<?php

    namespace App\Http;

$request = curl_init(self::$url);
curl_setopt_array($request,[
    CURLOPT_POST=>true,
    CURLOPT_POSTFIELDS=>[
        "event"=>$event,
        "data"=>$data
    ]
]);
return curl_exec($request);
