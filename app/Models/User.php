<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Services\DiscussionMembershipService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function discussions() : BelongsToMany{
        return $this->belongsToMany(Discussion::class, "discussions_memberships", "user_id", "discussion_id");
    }

    public function senderUser() : BelongsToMany{
        return $this->belongsToMany(User::class, "friendships", "receiver_user_id","sender_user_id");
    }

    public function receiverUser() : BelongsToMany{
        return $this->belongsToMany(User::class, "friendships", "sender_user_id", "receiver_user_id");
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'username',
        'birthdate',
        'pfp',
        'email',
        'quit',
        'password',
    ];

    public $incrementing = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'quit',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];
}
