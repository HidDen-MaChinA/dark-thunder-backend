<?php

namespace App\Http\DTOs\Message;

class CrupdateMessage {
    public function __construct(
        public string $id,
        public string $value,
        public string $user_id,
        public string $discussion_id
    ) { }
}
