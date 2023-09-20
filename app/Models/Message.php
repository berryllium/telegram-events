<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function getHtmlTextAttribute($value) {
        return "<p>" . str_replace("\r\n", "</p><p>", $this->text) . "</p>";
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->text = preg_replace(array('/<p>/', '/<\/p>/', '/<br>/i', '/<strong>/', '/<\/strong>/'), array('', "\r\n", "\r\n", '<b>', '</b>'), $model->text);;
        });
    }

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }

    public function author() : BelongsTo {
        return $this->belongsTo(Author::class);
    }

    public function message_schedules() : HasMany {
        return $this->hasMany(MessageSchedule::class);
    }

    public function message_files() : HasMany {
        return $this->hasMany(MessageFile::class);
    }
}
