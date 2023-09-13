<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function channels() : HasMany {
        return $this->hasMany(TelegramChannel::class);
    }
}
