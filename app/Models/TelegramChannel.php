<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tg_id',
        'name',
        'description'
    ];

    public function form() : BelongsTo {
        return $this->belongsTo(Form::class);
    }
}
