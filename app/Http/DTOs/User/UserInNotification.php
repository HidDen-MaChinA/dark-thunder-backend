<?php

namespace App\Http\DTOs\User;

class UserInNotification{
    public function __construct(
        public string $id,
        public string $username,
    ){ }
}
