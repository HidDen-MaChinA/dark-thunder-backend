<?php

use App\Http\Services\DiscussionMembershipService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscussionsMembershipController extends Controller
{
    public function __construct(
        private DiscussionMembershipService $discussionMembershipService
    ) { }

    public function findAllMembersOfDiscussion(Request $request){
        $validatedRequest = $request->validate([
            "discussion_id" => "required",
        ]);
        $page = $request->query("page", 1);
        return $this->discussionMembershipService->findAllDiscussionMembers($validatedRequest["discussion_id"], $page);
    }

    public function findAllDiscussionCurrentUserIsIn(Request $request){
        $page = $request->query("page", 1);
        return $this->discussionMembershipService->findAllDiscussionUserIsIn($page);
    }

    public function createDiscussionMembership(Request $request){
        $validatedRequest = $request->validate([
            "discussion_id" => "required",
            "user_id" => "required",
            "permission" => "regex:/read|write/u"
        ]);
        return $this->discussionMembershipService->createDiscussionMembership(
            $validatedRequest["discussion_id"],
            $validatedRequest["user_id"],
            $validatedRequest["permission"],
        );
    }

    public function updateDiscussionMembershipPermission(Request $request){
        $validatedRequest = $request->validate([
            "discussion_id" => "required",
            "user_id" => "required",
            "permission" => "regex:/read|write|mod/u"
        ]);
        return $this->discussionMembershipService->updateDiscussionMembershipPermission(
            $validatedRequest["discussion_id"],
            $validatedRequest["user_id"],
            $validatedRequest["permission"],
        );
    }

}
