<?php

namespace App\Actions\Report;

use App\Models\Message;
use Illuminate\Support\Collection;

abstract class Report
{
    protected Collection $messages;
    abstract protected function process();

    protected function getMessagesForPeriod($from, $to) {
        return Message::query()
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->where('telegram_bot_id', session('bot'))
            ->filter(['deleted' => true])
            ->with('message_schedules')
            ->with('author')
            ->get();
    }
}
