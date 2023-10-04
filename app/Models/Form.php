<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'template',
        'description'
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }
}
