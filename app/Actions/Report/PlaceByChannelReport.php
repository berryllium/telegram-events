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
                    $channels[$channel->id]['id'] = $channel->id;
                    $channels[$channel->id]['link'] = $channels[$channel->id]['link'] ?? '<a href="' . route('channel.edit', $channel->id) . '" >' . $channel->name . '</a>';
                    $channels[$channel->id]['total_messages' ][] = $schedule->message_id;
                    $channels[$channel->id]['total_sending'  ] = ($channels[$channel->id]['total_sending'   ] ?? 0) + 1;
                    $channels[$channel->id]['success_sending'] = ($channels[$channel->id]['success_sending' ] ?? 0) + ($channel->pivot->sent ? 1 : 0);
                    $channels[$channel->id]['wait_sending'   ] = ($channels[$channel->id]['wait_sending'    ] ?? 0) + (!$channel->pivot->sent && !$channel->pivot->error ? 1 : 0);
                    $channels[$channel->id]['error_sending'  ] = ($channels[$channel->id]['error_sending' ] ?? 0) + ($channel->pivot->error ? 1 : 0);
                }
            }
        }

        
        foreach($channels as $k => $arr) {
            $params = ['from' => $data['from'], 'to' => $data['to'], 'type' => 'PostLinks', 'place' => $data['place'], 'channel' => $arr['id']];
            $total = count(array_unique($arr['total_messages']));
            $channels[$k]['total_messages'] =  $total;
            $channels[$k]['total_sending'  ] =  '<a target="_blank" href="' . route('report.process', [...$params]) . '">' . $channels[$k]['total_sending'] . '</a>';
            $channels[$k]['success_sending'] =  '<a target="_blank" href="' . route('report.process', [...$params, 'sent' => 1, 'error' => 0]) . '">' . $channels[$k]['success_sending'] . '</a>';
            $channels[$k]['wait_sending'] =     '<a target="_blank" href="' . route('report.process', [...$params, 'sent' => 0, 'error' => 0]) . '">' . $channels[$k]['wait_sending'] . '</a>';
            $channels[$k]['error_sending'] =    '<a target="_blank" href="' . route('report.process', [...$params, 'sent' => 0, 'error' => 1]) . '">' . $channels[$k]['error_sending'] . '</a>';
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
