<?php

namespace App\Http\Services;

use App\Models\Discussion;

use function Termwind\terminal;

class DiscussionService{
    public function __construct() { }
    public function crupdateDiscussion(string $name, string $creator_id, string | null $id, string | null $messageRestrictionRegex)
    {
        $usedId = $id || uuid_create();
        Discussion::query()->updateOrCreate(
            [
                "id" => $usedId,
                "name" => $name
            ],[
                "creator_id" => $creator_id,
                "message_restriction_regex" => $messageRestrictionRegex
            ]
        );
        return $name;
    }

    public function findAllDiscussionsCreated($creatorId, $page){
        $baseNumber = $page <= 0 ? 10 : $page * 10;
        return Discussion::query()->get()->where("creator_id", $creatorId)->range($baseNumber - 9, $baseNumber);
    }
}
