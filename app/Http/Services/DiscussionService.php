<?php

namespace App\Http\Services;

use App\Models\Discussion;
use App\Models\DiscussionsMembership;
use exception;
use Illuminate\Support\Facades\Auth;

class DiscussionService{
    public function __construct(
        private DiscussionMembershipService $discussionMembershipService,
        private Discussion $discussionModel
    ) { }
    public function createDiscussion(string $name, string | null $messageRestrictionRegex){
        $currentUser = auth()->user();
        $usedId = uuid_create();
        $toSave = new Discussion([
            'id' => $usedId,
            'name' => $name,
            'creator_id' => $currentUser->id,
            'message_restriction_regex' => $messageRestrictionRegex
        ]);
        $toSave->save();
        $memberShipToSave = new DiscussionsMembership([
            "id" => uuid_create(),
            "user_id" => $currentUser->id,
            "discussion_id" => $usedId,
            "add_date" => now()->toDateTimeString(),
            "permission" => "mod"
        ]);
        $memberShipToSave->save();
        return $toSave;
    }

    public function updateDiscussion(string $name, string $id, string | null $messageRestrictionRegex)
    {
        return Discussion::query()->where('id', $id)->update(
            [
                "message_restriction_regex" => $messageRestrictionRegex,
                "name" => $name
            ]
        );
    }

    public function createDiscussionWithAnotherUser($userId, $discussionName){
        $currentUser = auth()->user();
        $newdiscussionId = uuid_create();
        $toSave = new Discussion([
            'id' => $newdiscussionId,
            'name' => $discussionName,
            'creator_id' => $currentUser->id
        ]);
        $toSave->save();
        $discussionsToSave = [
            [
                "id" => uuid_create(),
                "user_id" => $currentUser->id,
                "discussion_id" => $newdiscussionId,
                "add_date" => now()->toDateTimeString(),
                "permission" => "mod"
            ], [
                "id" => uuid_create(),
                "user_id" => $$userId,
                "discussion_id" => $newdiscussionId,
                "add_date" => now()->toDateTimeString(),
                "permission" => "mod"
            ]
        ];
        DiscussionsMembership::query()->getQuery()->insert($discussionsToSave);
        return $toSave;
    }

    public function findAllDiscussionUserIsIn(){
        $currentUser = auth()->user();
        $result = Discussion::query()->whereHas("discussionMembership", function ($query) use ($currentUser) {
            $query->where("user_id", $currentUser->id);
        })->paginate(20);
        return $result;
    }

}
