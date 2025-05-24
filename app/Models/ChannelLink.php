<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelLink extends Model
{
    protected $fillable = [
        'name',
        'link',
    ];

    public function channel() : BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
