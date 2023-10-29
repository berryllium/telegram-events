<?php

namespace App\Actions\Report;

use Carbon\Carbon;

class PlaceReport extends Report
{
    public function handle($data) {
        $places = [];
        $from = Carbon::parse($data['from']);
        $to = Carbon::parse($data['to']);

        if($to <= $from) {
            return back()->with('error', __('webapp.reports.time_error'));
        }

        $messages = $this->getMessagesForPeriod($from, $to);
        foreach($messages as $message) {
            if(!isset($places[$message->place_id])) {
                $places[$message->place_id] = [
                    'name' => $message->place->name,
                    'link' => route('place.edit', $message->place),
                    'total_messages' => 0,
                    'total_sending' => 0,
                    'success_sending' => 0,
                    'wait_sending' => 0,
                    'error_sending' => 0,
                ];
            }
            $places[$message->place_id]['total_messages'] ++;
            foreach ($message->message_schedules as $schedule) {
                $places[$message->place_id]['total_sending'] ++;
                $places[$message->place_id]["{$schedule->status}_sending"]++;
            }
        }
        return view('report.result_place', [
            'places' => $places,
            'title' => __('webapp.report') . ' - ' . __('webapp.places.place'),
            'period' => "$from - $to"
        ]);
    }

    protected function process()
    {

    }
}
