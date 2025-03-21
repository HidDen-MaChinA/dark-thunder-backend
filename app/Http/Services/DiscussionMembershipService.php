<?php

namespace App\Http\Services;

use App\Models\Discussion;
use App\Models\DiscussionsMembership;

use exception;
use Illuminate\Support\Facades\DB;

use function Termwind\terminal;

class DiscussionMembershipService{
    public function __construct() { }
    public function createDiscussionMembership($discussionId, $userId, $permission){
        if(!$this->isMod($discussionId)){
            throw new exception("only mod can perfom this action");
        }
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

    public function updateDiscussionMembershipPermission($discussionId, $userId, $permission){
        if(!$this->isMod($discussionId)){
            throw new exception("only mod can perfom this action");
        }
        return DiscussionsMembership::query()
            ->where("discussion_id", $discussionId)
            ->where("user_id", $userId)
            ->update([
                "permission" => $permission
            ]);
    }


    private function isMod($discussionId){
        $currentUser = auth()->user();
        $userDiscussionMembership = DiscussionsMembership::query()
            ->where("discussion_id", $discussionId)
            ->where("user_id", $currentUser->id)
            ->get();
        return $userDiscussionMembership == 'mod';
    }
}
