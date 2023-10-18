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
        'working_hours',
        'additional_info',
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
}
