<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageFile extends File
{
    public function message() : BelongsTo {
        return $this->belongsTo(Message::class);
    }
}
