<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlaceFile extends File
{
    use HasFactory;

    public function message() : BelongsTo {
        return $this->belongsTo(Place::class);
    }
}
