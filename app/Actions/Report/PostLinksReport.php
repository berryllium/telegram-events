<?php

namespace App\Actions\Report;

use App\Models\MessageSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostLinksReport extends Report
{
    public function handle($data)
    {
        $query = MessageSchedule::query()->withTrashed()->with('channels');

        $query->where('created_at', '>', Carbon::parse($data['from']))->where('created_at', '<', Carbon::parse($data['to']));

        $query->when($data['place']   ?? null, fn($q) => $q->whereHas('message', fn($q) => $q->where('place_id', $data['place'])));

        $channelFilter = function ($q) use ($data) {
            $q->when(
                $data['channel'] ?? null,
                fn($q) => $q->where('channel_message_schedule.channel_id', (int) $data['channel'])
            )->when(
                isset($data['sent']),
                fn($q) => $q->where('channel_message_schedule.sent', (int) $data['sent'])
            )->when(
                $data['error'] ?? null,
                fn($q) => $q->whereNotNull('channel_message_schedule.error')
            );
        };

        $query->when(
            $data['channel'] ?? $data['sent'] ?? $data['error'] ?? null,
            fn($q) => $q->whereHas('channels', $channelFilter)
        );

        $query->with(['channels' => $channelFilter,]);

        $result = $query->get();

        $posts = [];

        foreach ($result as $messageSchedule) {
            foreach ($messageSchedule->channels as $channel) {
                $posts[] = [
                    'channelName' => $channel->name,
                    'channelLink' => "/channel/{$channel->id}/edit",
                    'messageLink' => "/message/{$messageSchedule->message_id}/edit",
                    'postLink' => $channel->pivot->link,
                    'sent' => $channel->pivot->sent,
                    'error' => $channel->pivot->error,
                    'date' => $messageSchedule->sending_date,
                ];
            }
        }

        return view('report.result_post_links', ['posts' => $posts, 'title' => 'Список ссылок']);
    }
}
