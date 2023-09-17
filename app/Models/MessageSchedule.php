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

    public static $statuses = [
        'wait' => 'Ожидание',
        'error' => 'Ошибка',
        'success' => 'Отправлено',
    ];

    public function getStatusNameAttribute()
    {
        return self::$statuses[$this->status] ?? $this->status;
    }

    public function telegram_channels() {
        return $this->belongsToMany(TelegramChannel::class);
    }

    public function message() {
        return $this->belongsTo(Message::class);
    }
}
