<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Channel extends Model
{
    use HasFactory;

    public static $types = [
        'tg',
        'vk'
    ];

    protected $table = 'channels';

    protected $fillable = [
        'tg_id',
        'name',
        'type',
        'description',
        'telegram_bot_id',
        'show_place',
        'show_address',
    ];

    public function places() : HasMany {
        return $this->hasMany(Place::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class);
    }

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }

    public function message_schedule() : BelongsToMany {
        return $this->belongsToMany(MessageSchedule::class)->withPivot(['sent', 'error', 'link']);
    }
}
