<?php

namespace App\Actions\Report;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlaceByChannelReport extends Report
{
    public function handle($data)
    {
        $channels = [];

        $from = Carbon::parse($data['from']);
        $to = Carbon::parse($data['to']);

        $messages = $this->getMessagesForPeriod($from, $to, $data['place'], true);




        foreach ($messages as $message) {
            foreach ($message->message_schedules as $schedule) {
                foreach($schedule->channels as $channel) {
                    $channels[$channel->id]['link'] = $channels[$channel->id]['link'] ?? '<a href="' . route('channel.edit', $channel->id) . '" >' . $channel->name . '</a>';
                    $channels[$channel->id]['total_messages' ][] = $schedule->message_id;
                    $channels[$channel->id]['total_sending'  ] = ($channels[$channel->id]['total_sending'   ] ?? 0) + 1;
                    $channels[$channel->id]['success_sending'] = ($channels[$channel->id]['success_sending' ] ?? 0) + ($channel->pivot_sent ? 1 : 0);
                    $channels[$channel->id]['wait_sending'   ] = ($channels[$channel->id]['wait_sending'    ] ?? 0) + (!$channel->pivot_sent && !$channel->pivot_error ? 1 : 0);
                    $channels[$channel->id]['error_sending'  ] = ($channels[$channel->id]['error_sending' ] ?? 0) + ($channel->pivot_error ? 1 : 0);
                }
            }
        }

        foreach($channels as $k => $arr) {
            $channels[$k]['total_messages'] = count(array_unique($arr['total_messages']));
        }

        $headers = [
            'Канал',
            __('webapp.reports.total_messages'),
            __('webapp.reports.total_sending'),
            __('webapp.reports.success_sending'),
            __('webapp.reports.wait_sending'),
            __('webapp.reports.error_sending'),
        ];
        return response()->json(['headers' => $headers, 'rows' => array_values($channels)]);
    }
}
