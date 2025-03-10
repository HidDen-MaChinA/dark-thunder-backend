<?php

namespace App\Http\DTOs\Message;

use App\Http\DTOs\Discussion\DiscussionInMessage;
use App\Http\DTOs\User\GetUser;

class CrupdateMessage {
    public function __construct(
        public string $id,
        public string $value,
        public GetUser $user,
        public DiscussionInMessage $discussion
    ) { }
}
