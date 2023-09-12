<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];

    public static $types = [
        'string' => 'Строка',
        'text' => 'Текстовое поле',
        'number' => 'Число',
        'date' => 'Дата',
        'checkbox' => 'Флажок',
        'radio' => 'Переключатель',
        'select' => 'Выпадающий список'
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
