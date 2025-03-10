<?php

namespace App\Http\DTOs\Discussion;

use App\Http\DTOs\User\GetUser;

class GetDiscussion{
    public function __construct(
        public string $name,
        public string $id,
        public string $creator_name,
        public string $message_restriction_regex,
    ){ }
}
