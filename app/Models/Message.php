<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory, HasUuids;

    public function user() : HasOne {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function discussion() : HasOne {
        return $this->hasOne(Discussion::class, "id", "discussion_id");
    }

    protected $fillable = [
        'value',
        'user_id',
        'discussion_id'
    ];

}
