<?php

namespace App\Console\Commands;

use App\Jobs\ProcessMessage;
use App\Models\MessageSchedule;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class RetryMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retry-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messageSchedules = MessageSchedule::query()
            ->whereHas('channels',
                fn ($query) => $query
                    ->whereNotNull('channel_message_schedule.error')
                    ->where('channel_message_schedule.tries', '<', 5)
                    ->where('channel_message_schedule.updated_at', '<', Carbon::now()->subMinutes(5))
            )
            ->limit(100)
            ->get();
        ;
        foreach ($messageSchedules as $messageSchedule) {
            foreach ($messageSchedule->channels as $channel) {
                $messageSchedule->update(['status' => 'process']);
                ProcessMessage::dispatch($messageSchedule, $channel)->onQueue($channel->type);
                $this->output->info('retry id = ' . $messageSchedule->id);
            }
        }
    }
}
