<?php

namespace App\Facades;

class TechBotFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tech_bot.service';
    }
}