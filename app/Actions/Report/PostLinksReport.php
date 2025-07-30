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

        $query->where('created_at', '>', $data['from'])->where('created_at', '<', $data['to']);

        $query->when($data['place']   ?? null, fn($q) => $q->whereHas('message', fn($q) => $q->where('place_id', $data['place'])));

        $query->when(
            $data['channel'] ?? null,
            fn($q) => $q->whereHas(
                'channels',
                fn($q) => $q->where('channel_message_schedule.channel_id', $data['channel'])
                    ->when(
                        $data['sent'] ?? null,
                        fn($q) => $q->where('channel_message_schedule.sent', $data['sent'])
                    )
                    ->when(
                        $data['error'] ?? null,
                        fn($q) => $q->where('channel_message_schedule.error', (int) $data['error'])
                    )
            )
        );

        $query->with([
            'channels' => fn($q) => $q
                ->when(
                    $data['channel'] ?? null,
                    fn($q) => $q->where('channel_message_schedule.channel_id', $data['channel'])
                )
                ->when(
                    $data['sent'] ?? null,
                    fn($q) => $q->where('channel_message_schedule.sent', $data['sent'])
                )
                ->when(
                    $data['error'] ?? null,
                    fn($q) => $q->where('channel_message_schedule.error', (int) $data['error'])
                ),
        ]);

        $result = $query->get();

        $posts = [];

        foreach ($result as $messageSchedule) {
            foreach ($messageSchedule->channels as $channel) {
                $posts[] = [
                    'channelName' => $channel->name,
                    'channelLink' => "/channel/{$channel->id}/edit",
                    'messageLink' => "/message/{$messageSchedule->message_id}/edit",
                    'postLink' => $channel->pivot->link,
                    'date' => $messageSchedule->sending_date,
                ];
            }
        }

        return view('report.result_post_links', ['posts' => $posts, 'title' => 'Список ссылок']);
    }
}
