<?php

namespace App\Facades;

use App\Models\Message;

/**
 * @method static array createMedia(Message $message, $text = false)
 * @method static string send(string $message)
 */
class TechBotFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tech_bot.service';
    }
}