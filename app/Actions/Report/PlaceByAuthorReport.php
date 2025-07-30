<?php

namespace App\Actions\Report;

use Carbon\Carbon;

class PlaceByAuthorReport extends Report
{
    public function handle($data)
    {
        $authors = [];
        $from = Carbon::parse($data['from']);
        $to = Carbon::parse($data['to']);

        $messages = $this->getMessagesForPeriod($from, $to, $data['place']);

        foreach ($messages as $message) {
            if (!$message->author_id) continue;
            if (!isset($authors[$message->author_id])) {
                $authors[$message->author_id] = [
                    'link' => '<a href="' . route('author.edit', $message->place) . '">' . $message->author->name . '</a>',
                    'total_messages' => 0,
                    'total_sending' => 0,
                    'success_sending' => 0,
                    'wait_sending' => 0,
                    'error_sending' => 0,
                ];
            }
            $authors[$message->author_id]['total_messages']++;
            foreach ($message->message_schedules as $schedule) {
                $authors[$message->author_id]['total_sending']++;
                $authors[$message->author_id]["{$schedule->status}_sending"]++;
            }
        }

        $headers = [
            __('webapp.author'),
            __('webapp.reports.total_messages'),
            __('webapp.reports.total_sending'),
            __('webapp.reports.success_sending'),
            __('webapp.reports.wait_sending'),
            __('webapp.reports.error_sending'),
        ];
        return response()->json(['headers' => $headers, 'rows' => array_values($authors)]);
    }
}
