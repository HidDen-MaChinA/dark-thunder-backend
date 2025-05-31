<?php

namespace App\Models;

use App\Http\Services\DiscussionMembershipService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Discussion extends Model
{
    use HasFactory, HasUuids;

    public function creator() : HasOne{
        return $this->hasOne(User::class, "id", "creator_id");
    }

    public function members() : BelongsToMany{
        return $this->belongsToMany(User::class, "discussions_memberships", "discussion_id", "user_id");
    }

    protected $fillable = [
        'id',
        'name',
        'image',
        'message_restriction_regex',
        'creator_id',
    ];

    public $incrementing = false;
}
