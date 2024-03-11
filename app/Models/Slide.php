<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends File
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'name',
        'link'
    ];

    public function slider() : BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }
}
