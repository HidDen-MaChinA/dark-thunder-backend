<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends Model
{
    use HasFactory, HasUuids;

    public function mods() {
        return $this->hasMany(DiscussionsMembership::class, "discussion_id", "id")->getQuery()->where('permission', 3)->get();
    }

    protected $fillable = [
        'id',
        'name',
        'messages_restriction_regex',
        'creator_id',
    ];
}
