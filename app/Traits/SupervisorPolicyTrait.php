<?php

namespace App\Traits;

use App\Models\TelegramBot;
use App\Models\User;
use Illuminate\Auth\Access\Response;

trait SupervisorPolicyTrait
{
    /**
     * @return Response|bool|void
     */
    public function before(?User $user, $ability) {
        if(($connectedBot = $this->getBot()) && $connectedBot->id != session('bot')) {
            return Response::deny(__('webapp.wrong_current_bot',[
                'bot' => TelegramBot::find(session('bot'))?->name,
                'requested_bot' => $connectedBot?->name
            ]));
        } elseif($user?->hasRole('supervisor')) {
            return true;
        }
    }
}