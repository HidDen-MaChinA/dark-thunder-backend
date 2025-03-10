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
        return $this->hasone(Discussion::class, "id");
    }

    public function user() : HasOne{
        return $this->hasone(User::class, "id");
    }

    protected $fillable = [
        'user',
        'discussion',
        'add_date',
        'permission',
    ];
}
