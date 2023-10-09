<?php

namespace App\Models;

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

    public static $statuses = [
        'wait' => 'Ожидание',
        'error' => 'Ошибка',
        'success' => 'Отправлено',
    ];

    public function getStatusNameAttribute()
    {
        return __($this->status);
    }

    public function getStatusClassAttribute() {
        $map = [
            'wait' => 'warning',
            'error' => 'danger',
            'success' => 'success'
        ];
        return $map[$this->status] ?? null;
    }

    public function channels() {
        return $this->belongsToMany(Channel::class);
    }

    public function message() {
        return $this->belongsTo(Message::class);
    }

    public function scopeFilter(Builder $query, array $filters) : Builder
    {
        return $query
            ->when(
                $filters['search'] ?? false,
                fn ($query, $value) => $query->whereHas('message',
                    fn($q) => $q->where('text', 'like', "%$value%")
                )
            )
            ->when(
                $filters['telegram_bot'] ?? false,
                fn ($query, $value) => $query->whereHas('message',
                    fn($q) => $q->whereHas('telegram_bot',
                        fn($qa) => is_array($value) ? $qa->whereIn('id', $value) : $qa->where('id', $value)
                    )
                )
            )
            ->when(
                $filters['status'] ?? false,
                fn ($query, $value) => $query->where('status', $value)
            )
            ->orderBy('sending_date', 'desc');
    }
}
