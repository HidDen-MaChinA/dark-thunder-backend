<?php

namespace App\Http\DTOs\Discussion;

use App\Http\DTOs\User\GetUser;

class DiscussionInMessage{
    public function __construct(
        public string $name,
        public string $id,
        public string $pfp
    ){ }
}
