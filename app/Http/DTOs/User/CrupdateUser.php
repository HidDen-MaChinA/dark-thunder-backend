<?php

namespace App\Http\DTOs\User;

use Illuminate\Support\Facades\Date;

class CrupdateUser {
    public function __construct(
        public string | null $id,
        public string $firstname,
        public string $lastname,
        public string $username,
        public string $birthdate,
        public string | null $pfp,
        public string $email,
        public string $password
    ){ }
}
