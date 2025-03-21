<?php

namespace App\Http\Services;

use App\Models\Discussion;
use App\Models\DiscussionsMembership;

use exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
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

    public function findAllDiscussionUserIsIn($page){
        $currentUser = auth()->user();
        $result = DiscussionsMembership::query()->where("user_id", $currentUser->id)->paginate(20,null, null,$page);
        return collect($result->items())->map(function (DiscussionsMembership $value){
            return $value->discussion;
        });
    }

    public function findAllDiscussionMembers($discussionId,$page){
        $result = DiscussionsMembership::query()->where("discussion_id", $discussionId)->paginate(20, null, null,$page);
        return collect($result->items())->map(function (DiscussionsMembership $value){
            return $value->user;
        });
    }

    /*  used to know if the currently authenticated user who try to do
     something in a discussion is actually a moderator. */
    private function isMod($discussionId){
        $currentUser = auth()->user();
        $userDiscussionMembership = DiscussionsMembership::query()
            ->where("discussion_id", $discussionId)
            ->where("user_id", $currentUser->id)
            ->get();
        return $userDiscussionMembership == 'mod';
    }
}
