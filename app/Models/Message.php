<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'text',
        'data',
        'allowed',
        'place_id',
    ];

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    public function getHtmlTextAttribute($value) {
        return str_replace("\r\n", "<br>", $this->text);
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->text = preg_replace(
                ['/<p>/', '/<\/p>/', '/<br>/i', '/<strong>/', '/<\/strong>/'],
                ['', "\r\n", "\r\n", '<b>', '</b>'],
                $model->text
            );
            $model->text = trim($model->text, "&nbsp;\r\n");
            $model->text = str_replace('&nbsp;', "\r\n", $model->text);
        });
        static::deleting(function($model){
            foreach ($model->message_schedules as $schedule) {
                $schedule->delete();
            }
            foreach ($model->message_files as $file) {
                $file->delete();
            }
        });
    }

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }

    public function place() : BelongsTo {
        return $this->belongsTo(Place::class);
    }

    public function author() : BelongsTo {
        return $this->belongsTo(Author::class);
    }

    public function message_schedules() : HasMany {
        return $this->hasMany(MessageSchedule::class)->withTrashed();
    }

    public function message_files() : HasMany {
        return $this->hasMany(MessageFile::class);
    }

    public function scopeFilter(Builder $query, array $filters) : Builder
    {
        return $query
            ->when(
                $filters['search'] ?? false,
                fn ($query, $value) => $query->where(
                    fn($q) => $q->where('text', 'like', "%$value%"))->orWhereHas('message.author',
                    fn($q) => $q->where('name', 'like', "%$value%")
                )
            )
            ->when(
                $filters['telegram_bot'] ?? false,
                    fn($query, $value) => $query->where('telegram_bot_id', $value)
            )
            ->when(
                $filters['status'] ?? false,
                fn ($query, $value) => $query->whereHas('message_schedules',
                    fn($q) => $q->where('status', $value)
                )
            )
            ->when(
                $filters['from'] ?? false,
                fn ($query, $value) => $query->whereHas('message_schedules',
                    fn ($q) => $q->where('sending_date', '>', Carbon::createFromTimeString($value))
                )
            )
            ->when(
                $filters['to'] ?? false,
                fn ($query, $value) => $query->whereHas('message_schedules',
                    fn ($q) => $q->where('sending_date', '<', Carbon::createFromTimeString($value))
                )
            )
            ->when(
                $filters['deleted'] ?? false,
                fn ($query) => $query->withTrashed()
            )
            ->orderBy('created_at', 'desc');
    }

}
