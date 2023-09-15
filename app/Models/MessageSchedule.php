<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'sending_date',
        'status',
        'error_text'
    ];

    public function telegram_channels() {
        return $this->belongsToMany(TelegramChannel::class, 'message_schedules_telegram_channels');
    }

    public function message() {
        return $this->belongsTo(Message::class);
    }
}
