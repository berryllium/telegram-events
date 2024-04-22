<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $appends = ['src'];
    protected $fillable = [
        'name',
        'image',
        'description',
    ];

    function place() : BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function getSrcAttribute() {
        return asset(Storage::url($this->image));
    }
}
