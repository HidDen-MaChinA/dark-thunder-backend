<?php

namespace App\Http\Controllers;

use App\Http\Services\FriendshipService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FriendshipController extends Controller{
    public function __construct(
        public FriendshipService $friendshipService
    )
    { }

    public function create(Request $request){
        $validatedRequest = $request->validate([
            "user_id" => "uuid|required"
        ]);
        return $this->friendshipService->createUserFriendship($validatedRequest["user_id"]);
    }

    public function delete(Request $request){
        $validatedRequest = $request->validate([
            "friendship_id" => "uuid|required"
        ]);
       return $this->friendshipService->deleteUserFriendship($validatedRequest["friendship_id"]);
    }

    public function allowUserFriendship(Request $request){
        $validatedRequest = $request->validate([
            "friendship_id" => "uuid|required"
        ]);
       return $this->friendshipService->allowUserFriendship($validatedRequest["friendship_id"]);
    }

    public function findAllNotAllowedUserFriendshipReceived(){
        $toReturn = $this->friendshipService->findAllNotAllowedUserFriendshipReceived();
        return $toReturn->items();
    }

    public function findAllNotAllowedUserFriendshipSent(Request $request){
        $toReturn = $this->friendshipService->findAllNotAllowedUserFriendshipSent();
        return $toReturn->items();
    }
}
