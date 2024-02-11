<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'address_link',
        'description',
        'working_hours',
        'additional_info',
        'tag_set'
    ];

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }

    /**
     * @return array<Channel>
     */
    public function channels() : BelongsToMany
    {
        return $this->belongsToMany(Channel::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class);
    }

    public function tag() : HasOne {
        return $this->hasOne(Dictionary::class, 'id', 'tag_set');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
