<?php

namespace App\Actions\Report;

use Carbon\Carbon;

class AuthorReport extends Report
{
    public function handle($data) {
        $authors = [];
        $from = Carbon::parse($data['from']);
        $to = Carbon::parse($data['to']);

        if($to <= $from) {
            return back()->with('error', __('webapp.reports.time_error'));
        }

        $messages = $this->getMessagesForPeriod($from, $to);
        foreach($messages as $message) {
            if(!isset($authors[$message->author_id])) {
                $authors[$message->author_id] = [
                    'name' => $message->author->name,
                    'link' => route('author.edit', $message->author),
                    'total_messages' => 0,
                    'total_sending' => 0,
                    'success_sending' => 0,
                    'wait_sending' => 0,
                    'error_sending' => 0,
                ];
            }
            $authors[$message->author_id]['total_messages'] ++;
            foreach ($message->message_schedules as $schedule) {
                $authors[$message->author_id]['total_sending'] ++;
                $authors[$message->author_id]["{$schedule->status}_sending"]++;
            }
        }
        return view('report.result_author', [
            'authors' => $authors,
            'title' => __('webapp.report') . ' - ' . __('webapp.author'),
            'period' => "$from - $to"
        ]);
    }

    protected function process()
    {

    }
}
