<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TelegramBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'api_token',
        'moderation_group',
        'description'
    ];

    public function form() : BelongsTo {
        return $this->belongsTo(Form::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class);
    }

}
