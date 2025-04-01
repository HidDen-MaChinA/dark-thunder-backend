<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Friendship extends Model
{
    use HasFactory;


    public function senderUser() : HasOne {
        return $this->hasOne(User::class, "id", "sender_user_id");
    }

    public function receiverUser() : HasOne {
        return $this->hasOne(User::class, "id", "receiver_user_id");
    }

    protected $fillable = [
        "send_user_id",
        "receiver_user_id",
        "allowed"
    ];


    public $incrementing = false;
    public $timestamps = false;

}
