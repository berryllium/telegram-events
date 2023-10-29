<?php

namespace App\Actions\Report;

use App\Models\Message;
use Illuminate\Support\Collection;

abstract class Report
{
    protected Collection $messages;
    abstract protected function process();

    protected function getMessagesForPeriod($from, $to) {
        return Message::query()->filter([
            'from' => $from,
            'to' => $to,
            'deleted' => true,
            'telegram_bot' => session('bot'),
        ])->with('message_schedules')->with('message_schedules')->get();
    }
}
