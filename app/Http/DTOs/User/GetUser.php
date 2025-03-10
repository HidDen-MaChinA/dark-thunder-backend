<?php

namespace App\Http\DTOs\User;

class GetUser{
    public function __construct(
        public string $id,
        public string $firstname,
        public string $lastname,
        public string $username,
        public string $email,
    ){ }
}
