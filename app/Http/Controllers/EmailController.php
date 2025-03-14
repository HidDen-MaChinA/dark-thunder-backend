<?php

namespace App\Http\Controllers;

use App\Http\Services\EmailService;
use App\Models\Email;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailController extends Controller
{
    public function __construct(
        public EmailService $emailService,
        public Email $email
    ) { }
    public function sendVerificationCode(Request $request){
        $email = $request->validate([
            "email" => "email"
        ])["email"];

        return $this->emailService->sendVerificationCode($email);
    }

    public function verifyEmail(Request $request){
        $requestBody = $request->validate([
            "verification_code" => "regex:\^[0-9]{6,6}$\g|required",
            "email"=>"email|required",
            "verification_count_down" => "required"
        ]);

        return $this->emailService->verifyEmail($requestBody["verification_code"], $requestBody["email"], $requestBody["verification_count_down"]);
    }

}
