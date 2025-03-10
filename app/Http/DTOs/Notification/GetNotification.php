<?php

namespace App\Http\DTOs\Notification;

use App\Http\DTOs\Discussion\DiscussionInMessage;
use App\Http\DTOs\User\UserInNotification;

class GetNotification{
    public function __construct(
        public string $value,
        public UserInNotification $user,
        public DiscussionInMessage $discussion
    ) { }
}
