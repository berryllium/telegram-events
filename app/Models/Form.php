<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function bot() : HasOne {
        return $this->hasOne(TelegramBot::class);
    }
}
