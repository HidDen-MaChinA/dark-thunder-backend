<?php

namespace App\Http\Controllers;

use App\Http\Services\DiscussionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use exception;

class DiscussionController extends Controller
{
    public function __construct(
        public DiscussionService $discussionService
    )
    {

    }
    public function crupdateDiscussion(Request $request){
        $validatedDiscussion = $request->validate([
            "name" => "required",
            "creator_id" => "required",
            "id" => "nullable",
            "message_restriction_regex" => "nullable"
        ]);
        $name = $this->discussionService->crupdateDiscussion(...$validatedDiscussion);
        return response()->json([
            "name" => $name
        ]);
    }

    public function findAllDiscussionsCreated(Request $request){
        $page = $request->query("page");
        $creatorId = auth()->user()->id;
        return response()->json($this->discussionService->findAllDiscussionsCreated($creatorId, $page));
    }

}
