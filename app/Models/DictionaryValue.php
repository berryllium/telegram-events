<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DictionaryValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'dictionary_id'
    ];

    public function dictionary() : BelongsTo {
        return $this->belongsTo(Dictionary::class);
    }
}
