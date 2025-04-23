<?php

namespace App\Http\Controllers;

use App\Http\Services\DiscussionMembershipService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscussionsMembershipController extends Controller
{
    public function __construct(
        private DiscussionMembershipService $discussionMembershipService
    ) { }

    public function findAllMembersOfDiscussion(Request $request){
        $discussionId = $request->query("discussion_id");
        return $this->discussionMembershipService->findAllDiscussionMembers($discussionId);
    }

    public function createDiscussionMembership(Request $request){
        $validatedRequest = $request->validate([
            "discussion_id" => "required",
            "user_id" => "required",
            "permission" => ["regex:/read|write/u"]
        ]);
        return $this->discussionMembershipService->createDiscussionMembership(
            $validatedRequest["discussion_id"],
            $validatedRequest["user_id"],
            $validatedRequest["permission"],
        );
    }

    public function deleteDiscussionMembership(Request $request){
        $validatedRequest = $request->validate([
            "discussion_id" => "required",
            "user_id" => "required",
        ]);

        return response()->json(["message" => $this->discussionMembershipService->deleteDiscussionMembership(
            $validatedRequest["discussion_id"],
            $validatedRequest["user_id"]
        )]);
    }

    public function updateDiscussionMembershipPermission(Request $request){
        $validatedRequest = $request->validate([
            "id" => "uuid|required",
            "discussion_id" => "required",
            "user_id" => "required",
            "permission" => "regex:/read|write|mod/u"
        ]);
        return $this->discussionMembershipService->updateDiscussionMembershipPermission(
            $validatedRequest["id"],
            $validatedRequest["discussion_id"],
            $validatedRequest["user_id"],
            $validatedRequest["permission"],
        );
    }

}
