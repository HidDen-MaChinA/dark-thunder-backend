<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DiscussionsMembership extends Model
{
    use HasFactory, HasUuids;

    public function discussion() : HasOne{
        return $this->hasOne(Discussion::class, "id", "discussion_id");
    }

    public function user() : HasOne{
        return $this->hasOne(User::class, "id", "user_id");
    }

    protected $fillable = [
        'id',
        'user_id',
        'discussion_id',
        'add_date',
        'permission',
    ];

    protected $hidden = [
        'user_id',
        'discussion_id'
    ];
}
