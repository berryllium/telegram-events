<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description',
    ];

    public function form() : BelongsTo {
        return $this->belongsTo(Form::class);
    }

    public function telegram_channels() : BelongsToMany
    {
        return $this->belongsToMany(TelegramChannel::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class);
    }
}
