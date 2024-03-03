<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'address_link',
        'description',
        'working_hours',
        'additional_info',
        'tag_set',
        'domain',
        'image',
        'phone',
        'email',
    ];

    protected $appends = [
        'image_src'
    ];

    public function telegram_bot() : BelongsTo {
        return $this->belongsTo(TelegramBot::class);
    }

    /**
     * @return array<Channel>
     */
    public function channels() : BelongsToMany
    {
        return $this->belongsToMany(Channel::class);
    }

    public function authors() : BelongsToMany {
        return $this->belongsToMany(Author::class);
    }

    public function tag() : HasOne {
        return $this->hasOne(Dictionary::class, 'id', 'tag_set');
    }

    public function messages() : HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getImageSrcAttribute()
    {
        return asset(Storage::url($this->image));
    }

    public function resolveRouteBinding($value, $field = null) {
        if (is_numeric($value)) {
            return parent::resolveRouteBinding($value, $field);
        } else {
            return $this->where('domain', $value)->firstOrFail();
        }
    }
}
