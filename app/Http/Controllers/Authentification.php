<?php

namespace App\Http\Controllers;

use App\Http\Mappers\User\GetUserMapper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Authentification extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct(public GetUserMapper $getUserMapper) { }

    public function login(Request $request){
        $userLogin = $request->validate([
            "email"=>"regex:\<|>\g, email",
            "password"=>"password, regex:\<|>\g"
        ]);
        $authentified = auth()->attempt($userLogin);
        if($authentified){
            $currentUser = auth()->user();
            return response()->json(["token"=> $currentUser->createToken($userLogin['email'] . $currentUser->getAuthIdentifier() . "_token")]);
        }else{
            return response()->json(["error" => "user not found"], 404);
        }
    }

    public function whoami(Request $request){
        return response()->json($this->getUserMapper->entityToDTO($request->user()));
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
