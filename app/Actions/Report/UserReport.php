<?php

namespace App\Actions\Report;

use App\Models\MessageLog;
use Carbon\Carbon;

class UserReport extends Report
{
    public function handle($data) {
        $total = [
            'delete' => [],
            'edit' => [],
        ];
        $users = [];
        $from = Carbon::parse($data['from']);
        $to = Carbon::parse($data['to']);

        if($to <= $from) {
            return back()->with('error', __('webapp.reports.time_error'));
        }

        $records = MessageLog::query()
            ->where('telegram_bot_id', session('bot'))
            ->where('created_at', '>', $from)
            ->where('created_at', '<', $to)
            ->get();


        foreach($records as $record) {
            if(!isset($users[$record->user_id])) {
                $users[$record->user_id] = [
                    'name' => $record->user->name,
                    'link' => route('user.edit', $record->user),
                    'delete' => [],
                    'edit' => [],
                ];
            }
            $users[$record->user_id][$record->action][$record->message_id] = $record->id;
            $total[$record->action][$record->message_id] = $record->id;
        }

        return view('report.result_user', [
            'users' => $users,
            'title' => __('webapp.report') . ' - ' . __('webapp.user'),
            'period' => "$from - $to",
            'total' => $total
        ]);
    }
}
