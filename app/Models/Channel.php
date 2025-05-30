<?php

namespace App\Models;

use App\Services\OKService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Channel extends Model
{
    use HasFactory;

    public static $types = [
        'tg',
        'vk',
        'ok',
        'wp',
    ];

    protected $table = 'channels';

    protected $fillable = [
        'tg_id',
        'name',
        'type',
        'token',
        'domain',
        'description',
        'telegram_bot_id',
        'show_place',
        'show_address',
        'show_work_hours',
        'show_links',
    ];

    public function places() : BelongsToMany {
        return $this->belongsToMany(Place::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class);
    }

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }

    public function message_schedule() : BelongsToMany {
        return $this->belongsToMany(MessageSchedule::class)->withPivot(['sent', 'error', 'tries', 'link']);
    }

    public function links() : HasMany
    {
        return $this->hasMany(ChannelLink::class);
    }

    public function getLinkAttribute() {
        if($this->type == 'ok') {
            return "https://ok.ru/group/$this->tg_id/";
        } elseif($this->type == 'vk') {
            return "https://vk.com/public{$this->tg_id}/";
        } elseif($this->type == 'wp') {
            return $this->domain . "?p={$this->tg_id}/";
        }
        return 'https://t.me/c/' . substr($this->tg_id, 4) . '/10000000';
    }

    public function subscribe($token)
    {
        if($this->type == 'ok') {
            OKService::subscribe($token);
        }
    }
}
