<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
        'telegram_bot_id',
        'action'
    ];

    public function message() : BelongsTo {
        return $this->belongsTo(Message::class);
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

}
