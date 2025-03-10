<?php

namespace App\Http\DTOs\User;

class CrupdateUser {
    public function __construct(
        public string | null $id,
        public string $firstname,
        public string $lastname,
        public string $username,
        public string $email,
        public string $password
    ){ }
}
