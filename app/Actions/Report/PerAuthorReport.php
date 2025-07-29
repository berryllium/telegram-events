<?php

namespace App\Actions\Report;

use App\Models\Author;
use App\Models\MessageSchedule;
use Illuminate\Support\Carbon;

class PerAuthorReport extends Report
{
    public function handle($data)
    {
        $authorName = Author::findOrFail($data['author'])->name;
        $from = Carbon::parse($data['from'])->startOfDay();
        $to = Carbon::parse($data['to'])->endOfDay();

        $posts = [];
        $statistics = [];

        $messageSchedules = MessageSchedule::query()
        ->whereBetween('created_at', [$from, $to])
        ->with('channels')
        ->whereHas(
            'message',
            fn($q) => $q->where('author_id', $data['author'])->where('telegram_bot_id', session('bot'))->withTrashed()
        ->orderBy('sending_date', 'asc')
        )->get();

        foreach($messageSchedules as $messageSchedule) {
            foreach($messageSchedule->channels as $channel) {
                $posts[] = [
                    'channelName' => $channel->name,
                    'channelLink' => "/channel/{$channel->id}/edit",
                    'messageLink' => "/message/{$messageSchedule->message_id}/edit",
                    'postLink' => $channel->pivot->link,
                    'date' => $messageSchedule->sending_date,
                ];
                $statistics[$channel->name]['success'] = ($statistics[$channel->name]['success'] ?? 0) + ($channel->pivot->link ? 1 : 0);
                $statistics[$channel->name]['wait'] = ($statistics[$channel->name]['wait'] ?? 0) + (!$channel->pivot->sent && !$channel->pivot->error ? 1 : 0);
                $statistics[$channel->name]['error'] = ($statistics[$channel->name]['error'] ?? 0) + ($channel->pivot->error ? 1 : 0);
            }
        }

        $total = [];
        foreach($statistics as $channel) {
            foreach($channel as $k => $v)
            $total[$k] = ($total[$k] ?? 0) + $v;
        }


        return view('report.result_per_author', [
            'title' => 'Отчет автору ' . $authorName,
            'period' => "$from - $to",
            'posts' => $posts,
            'statistics' => $statistics,
            'total' => $total,
        ]);
    }
}
