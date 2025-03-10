<?php

namespace App\Http\DTOs\Discussion;

class CrupdateDiscussion{
    public function __construct(
        public string $name,
        public string $id,
        public string $creator_id,
        public string $message_restriction_regex,
    ){ }
}
