<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'tg_id',
        'name',
        'username',
        'description',
        'trusted',
        'premium',
    ];

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function places() : BelongsToMany {
        return $this->belongsToMany(Place::class);
    }

    public function channels() : BelongsToMany {
        return $this->belongsToMany(Channel::class);
    }

    public function telegram_bots() : BelongsToMany {
        return $this->belongsToMany(TelegramBot::class)->withPivot(['trusted', 'title', 'description', 'can_select_channels', 'can_use_gigachat']);
    }
}
