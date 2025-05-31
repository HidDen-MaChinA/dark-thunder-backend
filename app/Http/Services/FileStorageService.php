<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorageService{
    public function saveFileAs(UploadedFile $file, string $name, string $path){
        $checkedPath = $this->checkPath($path);
        $fileDiectoryPath = "public/" . $checkedPath;
        Storage::putFileAs($fileDiectoryPath, $file, $name);
        return "storage/" . $checkedPath . "/" . $name;
    }
    private function checkPath(string $arg){
        $arr = str_split($arg);
        foreach($arr as $content){
            if($content != "/"){
                break;
            }
            if($content == "/"){
                array_shift($arr);
            }
        }
        return array_reduce($arr, function ($ax, $dx){return $ax . $dx;});
    }

}