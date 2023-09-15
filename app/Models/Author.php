<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'tg_id',
        'name',
        'username',
        'premium'
    ];

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
