<?php

namespace App\Console\Commands;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleting old messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $time = time();
        $days = config('app.messages_storage_period');
        while($time > time() - 50) {
            $message = Message::query()
                ->where('created_at', '<', Carbon::now()->subDays($days)->startOfDay())
                ->orderBy('id', 'desc')
                ->limit(1)
                ->first()
            ;

            if($message) {
                foreach ($message->message_files as $file) {
                    $file->delete();
                }
                $message->delete();
            } else {
                break;
            }
        }
    }
}
