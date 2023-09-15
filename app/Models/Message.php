<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'data',
        'allowed'
    ];

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    public function telegram_bot() {
        return $this->belongsTo(TelegramBot::class);
    }

    public function author() {
        return $this->belongsTo(Author::class);
    }

    public function massage_schedules() {
        return $this->hasMany(MessageSchedule::class);
    }
}
