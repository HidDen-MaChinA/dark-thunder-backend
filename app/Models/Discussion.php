<?php

namespace App\Models;

use App\Http\Services\DiscussionMembershipService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Discussion extends Model
{
    use HasFactory, HasUuids;

    public function creator() : HasOne{
        return $this->hasOne(User::class, "id", "creator_id");
    }

    public function discussionMembership() : HasMany{
        return $this->hasMany(DiscussionsMembership::class);
    }

    protected $fillable = [
        'id',
        'name',
        'messages_restriction_regex',
        'creator_id',
    ];

    public $incrementing = false;
}
