<?php

namespace App\Http\Controllers;

use App\Http\Mappers\UserMapper;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuthentificationController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct(public UserMapper $userMapper) { }

    public function login(Request $request){
        $userLogin = $request->only([
            "email",
            "password"
        ]);

        $authentified = auth()->attempt($userLogin);
        if($authentified){
            $currentUser = auth()->user();
            if($currentUser->quit){
                return response()->json(["reason"=>"The user login" . $currentUser->username . " you entered have already quit the application"], 403);
            }
            auth()->login($currentUser);
            return response()->json(["token"=> $currentUser->createToken($userLogin['email'] . $currentUser->getAuthIdentifier() . "_token")->plainTextToken]);
        }else{
            return response()->json(["error" => "user not found"], 404);
        }
    }

    public function whoami(Request $request){
        $user = $request->user();
        $dailyDiscussionsTokenCreationDate = $user->daily_discussions_token_creation_date;
        if(Carbon::createFromTimeString($dailyDiscussionsTokenCreationDate)->diffInDays(Carbon::now())>=1){
            $user->daily_discussions_token = uuid_create();
            $user->daily_discussions_token_creation_date = Carbon::now()->toDateTimeString();
            $user->save();
        }
        return response()->json($this->userMapper->entityToDTOGetUser($request->user()));
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
