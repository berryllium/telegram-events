<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramChannel extends Model
{
    use HasFactory;

    protected $table = 'telegram_channels';

    protected $fillable = [
        'tg_id',
        'name',
        'description',
    ];

    public function places() : HasMany {
        return $this->hasMany(Place::class);
    }

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }
}
