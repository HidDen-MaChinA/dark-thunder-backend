<?php

namespace App\Http\Controllers;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Date;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct(
        public UserService $userService
    ) {}

    public function quit(Request $request){
        $userPayload = $request->validate([
            "regex:\<|>\g",
            "email" => "email|required",
            "password" => "password|required"
        ]);
        return response()->json(["done" => $this->userService->quit($userPayload["password"], $userPayload["email"])]);
    }

    public function crupdate(Request $request)
    {
        $userPayload = $request->validate([
            "regex:\<|>\g",
            "id" => "nullable",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required",
            "birthdate" => "date",
            "pfp" => "nullable",
            "email" => "email|required",
            "password" => "required"
        ]);
        $crupdateUser = new CrupdateUser(
            null,
            $userPayload["firstname"],
            $userPayload["lastname"],
            $userPayload["username"],
            $userPayload["birthdate"],
            $userPayload["pfp"],
            $userPayload["email"],
            $userPayload["password"],
        );
        if(isset($userPayload["id"])){
            $crupdateUser->id=$userPayload["id"];
        }
        if ($crupdateUser->id != null) {
            $this->userService->update($crupdateUser) ? response()->json(["status" => "user updated"]) : response()->json(["status" => "user could not be updated"]);
        } else {
            $this->userService->create($crupdateUser) ?  response()->json(["status" => "user created"]) : response()->json(["status" => "user could not be created"]);
        }
    }
}
