<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'template',
        'description',
        'default_price_type'
    ];

    public static array $price_types = [
        'range',
        'min',
        'exact',
        'free',
        'no'
    ];

    /**
     * @return array<Field>
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function bots() : HasMany {
        return $this->HasMany(TelegramBot::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model){
            $model->fields()->create([
                'name' => __('webapp.places.place'),
                'code' => 'place',
                'type' => 'place',
            ]);
            $model->fields()->create([
                'name' => __('webapp.address'),
                'code' => 'address',
                'type' => 'address',
            ]);
        });
    }
}
