<?php

namespace App\Actions\Report;

use App\Models\Message;
use Illuminate\Support\Collection;

abstract class Report
{
    protected Collection $messages;

    protected function getMessagesForPeriod($from, $to, $place_id = null) {
        return Message::query()
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->where('telegram_bot_id', session('bot'))
            ->when($place_id, fn($q) => $q->where('place_id', $place_id))
            ->filter(['deleted' => true])
            ->select('id', 'author_id', 'place_id')
            ->with('message_schedules')
            ->with('author')
            ->get();
    }
}
