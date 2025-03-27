<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DiscussionsMembership extends Model
{
    use HasFactory, HasUuids;

    public function discussion() : BelongsTo{
        return $this->belongsTo(Discussion::class);
    }

    public function user() : HasOne{
        return $this->hasOne(User::class);
    }

    protected $fillable = [
        'id',
        'user_id',
        'discussion_id',
        'add_date',
        'permission',
    ];

    public $incrementing = false;
}
