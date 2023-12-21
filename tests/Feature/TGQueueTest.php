<?php

namespace Tests\Feature;

use App\Jobs\ProcessMessage;
use App\Models\Channel;
use App\Models\Message;
use App\Models\MessageSchedule;
use Carbon\Carbon;
use Tests\TestCase;

class TGQueueTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_tg_queue(): void
    {
        /** @var Message $message */
        $message = new Message([
            'text' => 'Test message text',
            'data' => '',
            'allowed' => true,
            'place_id' => 1,
            'author_id' => 1,
        ]);

        $message->author_id = 1;
        $message->telegram_bot_id = 1;
        $message->save();

        /** @var MessageSchedule $schedule */
        $schedule = $message->message_schedules()->create([
            'sending_date' => Carbon::now()->subDay(),
        ]);

        $channel = Channel::query()->find(1);
        ProcessMessage::dispatch($schedule, $channel)->onQueue($channel->type);

        $schedule->refresh();
        $this->assertTrue($schedule->status == 'success');
    }
}
