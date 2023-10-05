<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotAuthor extends Model
{
    use HasFactory;

    protected $table = 'author_telegram_bot';
    protected $fillable = [
        'title',
        'description'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function telegram_bot()
    {
        return $this->belongsTo(TelegramBot::class);
    }
}
