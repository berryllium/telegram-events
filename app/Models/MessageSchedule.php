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

    public function updateStatus() {
        $this->refresh();
        $sendings = $this->channels;
        if($sendings) {
            $failed_channels = [];
            $error_text = '';
            $status = 'success';
            foreach ($sendings as $sending) {
                if(!$sending->pivot->sent) {
                    $status = 'process';
                }
                if($sending->pivot->error) {
                    $failed_channels[] = $sending->name;
                }
            }
            if($failed_channels) {
                $status = 'error';
                $error_text = __('webapp.error_sending_channels', ['channels' => implode(', ', $failed_channels)]);
            }
            $this->update([
                'status' => $status,
                'error_text' => $error_text,
            ]);
        }
    }

}
