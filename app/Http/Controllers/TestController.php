<?php

namespace App\Http\Controllers;

use App\Facades\ImageCompressorFacade;
use App\Models\MessageSchedule;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function __invoke(): void
    {
        $messages = MessageSchedule::whereHas('channels', function (Builder $query) {
            $query->whereNotNull('channel_message_schedule.error')
                ->where('tries', '<', 5);
        })->get();
        dd($messages);
    }
}
