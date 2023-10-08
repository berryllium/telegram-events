<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dictionary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'telegram_bot_id'
    ];

    public function dictionary_values() : HasMany {
        return $this->hasMany(DictionaryValue::class);
    }

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }
}
