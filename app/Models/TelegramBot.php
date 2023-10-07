<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use TelegramBot\Api\BotApi;

class TelegramBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'api_token',
        'moderation_group',
        'description',
        'form_id'
    ];

    public function form() : BelongsTo {
        return $this->belongsTo(Form::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class)->withPivot(['trusted', 'title', 'description']);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function places() : HasMany {
        return $this->hasMany(Place::class);
    }

    public function setWebhook() {
        try {
            $botApi = new BotApi($this->api_token);
            $botApi->setWebhook(
                route('api.telegram'),
                null,
                null,
                40,
                null,
                false,
                $this->code);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), ['bot' => $this->id, 'url' => route('api.telegram')]);
            return false;
        }

    }

    public function unsetWebhook()
    {
        try {
            $botApi = new BotApi($this->api_token);
            $botApi->deleteWebhook();
            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), ['bot' => $this->id, 'url' => route('api.telegram')]);
            return false;
        }

    }

}
