<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        return $this->belongsToMany(Channel::class)->withPivot(['sent', 'error', 'link']);
    }

    public function message() {
        return $this->belongsTo(Message::class)->withTrashed();
    }

}
