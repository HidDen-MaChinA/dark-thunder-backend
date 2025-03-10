<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discussion extends Model
{
    use HasFactory, HasUuids;

    public function members() : HasMany{
        return $this->hasMany(User::class,"id");
    }

    public function messages() : HasMany{
        return $this->hasMany(Message::class,"id");
    }

    protected $fillable = [
        'name',
        'messages_restriction_regex',
        'creator_id',
    ];
}
