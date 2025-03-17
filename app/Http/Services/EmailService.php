<?php

namespace App\Http\Services;

use App\Models\Email;
use DateTime;
use Exception;
use Laravel\Prompts\Terminal;

use function Termwind\terminal;

class EmailService{
    public function __construct() { }
    public function sendVerificationCode($email){
        $verificationCode = "";
        for($i=0; $i<6; $i++){
            $verificationCode = $verificationCode . random_int(1, 9);
        }
        $verificationCountDown = now()->toDateTimeString();
        Email::updateOrCreate(
            [
                "email" => $email,
            ],[
                "verification_code" => $verificationCode,
                "verification_count_down" => $verificationCountDown
            ]
        );
        return $verificationCountDown . "|" .$verificationCode;
        // mail($email, "Darkthunder email validation", "your validation code is" . $verificationCode);
    }

    public function verifyEmail($verificationCode, $email, $verificationCountDown){
        $query = Email::where("email", $email)
            ->where("verification_code", $verificationCode)
            ->where("verification_count_down", $verificationCountDown);
        $toReturn = $query->first();
        if($toReturn == null){
            throw new exception("verification code does not match the given");
        }
        $dateDiff = now()->toDateTime()->diff(new DateTime($toReturn->verification_count_down));
        if($dateDiff->h > 2){
            throw new exception("the verification time exceed 2 hours, you no longer can verify email using this code");
        }

        $verifiedAt = now()->toDateTimeString();
        Email::updateOrCreate(
            [
                "email" => $email,
            ],[
                "verification_code" => null,
                "verification_count_down" => null,
                "verified_at" => $verifiedAt
            ]
        );
        return $verifiedAt;
    }

}
