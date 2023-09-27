<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read boolean $canHaveDictionary
 * @property-read ?string $typeName
 */
class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'dictionary_id'
    ];

    public static $types = [
        'string' => 'Строка',
        'text' => 'Текстовое поле',
        'number' => 'Число',
        'date' => 'Дата',
        'checkbox' => 'Флажок',
        'radio' => 'Переключатель',
        'select' => 'Выпадающий список',
        'place' => 'Место',
        'address' => 'Адрес',
        'files' => 'Файлы'
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function dictionary() : BelongsTo {
        return $this->belongsTo(Dictionary::class);
    }

    public function getTypeNameAttribute() {
        return self::$types[$this->type] ?? '';
    }

    public function getCanHaveDictionaryAttribute() {
        return !!in_array($this->type, ['select', 'checkbox', 'radio']);
    }
}
