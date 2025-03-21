<?php

namespace App\Http\Services;

use App\Models\Discussion;
use exception;

class DiscussionService{
    public function __construct(
        private DiscussionMembershipService $discussionMembershipService
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
        $this->discussionMembershipService->createDiscussionMembership($usedId, $currentUser->id, 'mod');
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
        $this->discussionMembershipService->createDiscussionMembership($newdiscussionId,$currentUser->id, 'mod');
        $this->discussionMembershipService->createDiscussionMembership($newdiscussionId,$userId, 'mod');
        return $toSave;
    }

    public function findAllDiscussionsCreated($page){
        $creatorId = auth()->user()->id;
        $baseNumber = $page <= 0 ? 10 : $page * 10;
        return Discussion::query()->get()->where("creator_id", $creatorId)->range($baseNumber - 9, $baseNumber);
    }
}
