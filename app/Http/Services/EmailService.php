<?php

namespace App\Http\Services;

use App\Models\Email;
use DateTime;
use Exception;

class EmailService{
    public function __construct(
        public Email $emailModel
    ) { }
    public function sendVerificationCode($email){
        $verificationCode = "";
        for($i=0; $i<6; $i++){
            $verificationCode = $verificationCode . random_int(1, 9);
        }
        $verificationCountDown = now()->toDateTime();
        $toSave = new Email([
            "email" => $email,
            "verification_code" => $verificationCode,
            "verification_count_down" => $verificationCountDown
        ]);
        $toSave->save();
        mail($email, "Darkthunder email validation", "your validation code is" . $verificationCode);
        return $verificationCountDown;
    }

    public function verifyEmail($verificationCode, $email, $verificationCountDown){
        $toReturn = $this->emailModel->all()
            ->where("email", $email)
            ->where("verification_code", $verificationCode)
            ->where("verification_count_down", $verificationCountDown)
            ->get(0);
        if(!isset($toReturn)){
            throw new exception("verification code does not match the given");
        }
        $dateDiff = now()->toDateTime()->diff(new DateTime($toReturn->verification_count_down));
        if($dateDiff->h > 2){
            throw new exception("the verification time exceed 2 hours, you no longer can verify email using this code");
        }

        $verifiedAt = now()->toDateTime();
        $toSave = new Email([
            "email" => $email,
            "verification_code" => null,
            "verification_count_down" => null,
            "verified_at" => $verifiedAt
        ]);
        $toSave->update();
        return $verifiedAt;
    }

}
