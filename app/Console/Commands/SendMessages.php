<?php

namespace App\Console\Commands;

use App\Jobs\ProcessMessage;
use App\Models\MessageSchedule;
use Illuminate\Console\Command;

class SendMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put new incoming messages into queues for further sending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messageSchedules = MessageSchedule::query()
            ->where('status', '=', 'wait')
            ->where('sending_date', '<=', now())
            ->whereHas('message', fn($q) => $q->where('allowed', true))
            ->limit(100)
            ->get();
;
        foreach ($messageSchedules as $messageSchedule) {
            foreach ($messageSchedule->channels as $channel) {
                ProcessMessage::dispatch($messageSchedule, $channel)->onQueue($channel->type);
            }
            $messageSchedule->update(['status' => 'process']);
        }
    }
}
