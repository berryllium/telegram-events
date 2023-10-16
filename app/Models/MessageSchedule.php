<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    public static array $statusMap = [
        'wait' => 'warning',
        'process' => 'info',
        'error' => 'danger',
        'success' => 'success',
    ];

    public function getStatusNameAttribute()
    {
        return __('webapp.'.$this->status);
    }

    public function getStatusClassAttribute() {
        return self::$statusMap[$this->status] ?? null;
    }

    public function channels() {
        return $this->belongsToMany(Channel::class)->withPivot(['sent', 'error']);
    }

    public function message() {
        return $this->belongsTo(Message::class);
    }

    public function scopeFilter(Builder $query, array $filters) : Builder
    {
        return $query
            ->when(
                $filters['search'] ?? false,
                fn ($query, $value) => $query->where(
                    fn ($query) => $query->whereHas('message',
                        fn($q) => $q->where('text', 'like', "%$value%"))->orWhereHas('message.author',
                            fn($q) => $q->where('name', 'like', "%$value%")
                    )
                )
            )
            ->when(
                $filters['telegram_bot'] ?? false,
                fn ($query, $value) => $query->whereHas('message',
                    fn($q) => $q->where('telegram_bot_id', $value)
                )
            )
            ->when(
                $filters['status'] ?? false,
                fn ($query, $value) => $query->where('status', $value)
            )
            ->when(
                $filters['from'] ?? false,
                fn ($query, $value) => $query->where('sending_date', '>', Carbon::createFromTimeString($value))
            )
            ->when(
                $filters['to'] ?? false,
                fn ($query, $value) => $query->where('sending_date', '<', Carbon::createFromTimeString($value))
            )
            ->orderBy('sending_date', 'desc');
    }
}
