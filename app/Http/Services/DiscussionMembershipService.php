<?php

namespace App\Http\Services;

use App\Models\Discussion;
use App\Models\DiscussionsMembership;

use exception;
use function Termwind\terminal;

class DiscussionMembershipService{
    public function __construct() { }
    public function createDiscussionMembership($discussionId, $userId, $permission){
        $usedId = uuid_create();
        $toSave = new DiscussionsMembership([
            "id" => $usedId,
            "user_id" => $userId,
            "discussion_id" => $discussionId,
            "add_date" => now()->toDateTimeString(),
            "permission" => $permission
        ]);
        $toSave->saveOrFail();
        return $toSave;
    }
}
