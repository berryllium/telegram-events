<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MessageFile extends Model
{
    use HasFactory;

    protected $fillable = ['filename'];
    protected $appends = ['src'];

    public function message() : BelongsTo {
        return $this->belongsTo(Message::class);
    }

    public function getSrcAttribute() {
        return asset(Storage::url($this->filename));
    }
}
