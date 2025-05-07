<?php

namespace App\Http\DTOs\User;

use Illuminate\Support\Facades\Date;

class SimplifiedUser{
    public function __construct(
        public string $id,
        public string $username,
        public ?string $pfp
    ){ }
}
