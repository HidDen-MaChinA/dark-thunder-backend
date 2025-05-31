<?php

namespace App\Http\DTOs\User;

use GuzzleHttp\Psr7\MultipartStream;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Date;

class CrupdateUser {
    public function __construct(
        public string | null $id,
        public string $firstname,
        public string $lastname,
        public string $username,
        public string $birthdate,
        public UploadedFile | null $pfp,
        public string $email,
        public string $password
    ){ }
}
