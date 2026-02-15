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
        'slug',
        'code',
        'api_token',
        'moderation_group',
        'comments_channel_id',
        'description',
        'form_id',
        'links',
    ];

    protected $casts = [
        'api_token' => 'encrypted'
    ];

    public function form() : BelongsTo {
        return $this->belongsTo(Form::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class)->withPivot(['trusted', 'title', 'description', 'can_select_channels', 'can_use_gigachat']);
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

    public function channels() : HasMany {
        return $this->hasMany(Channel::class);
    }

    public function setWebhook() : bool {
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

    public function unsetWebhook() : bool
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

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('id', $value)
            ->orWhere('slug', $value)
            ->firstOrFail();
    }

}
