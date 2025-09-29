<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteFailedMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-failed-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Удаляем записи по следущим признакам
     * - сообщение еще не было удалено
     * - все даты отправки сообщения должны быть старше двух недель (не должно иметь отправок с датой > сейчес - 2 недели)
     * - сообщение должно иметь хотя бы одну отправку в статусе ошибка
     */
    public function handle()
    {
        $time = time();
        $days = config('app.failed_messages_storage_period');

        while ($time > time() - 50) {
            $message = Message::query()
                ->whereNull('deleted_at')
                ->whereHas('message_schedules', fn(Builder $q) => $q->where('status', 'error'))
                ->whereDoesntHave('message_schedules', fn(Builder $q) => $q->where('sending_date', '>', Carbon::now()->subDays($days)->startOfDay()))
                ->orderBy('id')
                ->limit(1)
                ->first();

            if ($message) {
                foreach ($message->message_files as $file) {
                    $file->delete();
                }
                $message->delete();
                Log::info('Message with failed sendings has been deleted', ['message_id' => $message->id]);
            } else {
                break;
            }
        }
    }
}
