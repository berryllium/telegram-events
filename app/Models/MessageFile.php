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

    public function getPathAttribute() {
        return Storage::path($this->filename);
    }

    public function getSrcAttribute() {
        return asset(Storage::url($this->filename));
    }

    public function getMimeTypeAttribute() {
        return mime_content_type($this->path);
    }

    public function getTypeAttribute() {
        $mime = explode('/', $this->getMimeTypeAttribute());
        return $mime[0];
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($model){
            Storage::delete($model->path);
        });
    }
}
