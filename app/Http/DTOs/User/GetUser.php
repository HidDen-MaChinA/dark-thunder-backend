<?php

namespace App\Http\DTOs\User;

use Illuminate\Support\Facades\Date;

class GetUser{
    public function __construct(
        public string $id,
        public string $firstname,
        public string $lastname,
        public string $username,
        public string $birthdate,
        public string $pfp,
        public string $email,
    ){ }
}
