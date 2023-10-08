<?php

namespace App\Policies;

use App\Models\MessageSchedule;
use App\Models\User;
use App\Traits\SupervisorPolicyTrait;
use Illuminate\Auth\Access\Response;

class MessageSchedulePolicy
{
    use SupervisorPolicyTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MessageSchedule $messageSchedule): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MessageSchedule $messageSchedule): bool
    {
        return $user->hasAnyRole('admin', 'moderator') &&
            $user->telegram_bots->contains($messageSchedule->message->telegram_bot_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageSchedule $messageSchedule): bool
    {
        return $user->hasAnyRole('admin', 'moderator') &&
            $user->telegram_bots->contains($messageSchedule->message->telegram_bot_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageSchedule $messageSchedule): bool
    {
        return $user->hasAnyRole('admin', 'moderator') &&
            $user->telegram_bots->contains($messageSchedule->message->telegram_bot_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageSchedule $messageSchedule): bool
    {
        return $user->hasAnyRole('admin', 'moderator') &&
            $user->telegram_bots->contains($messageSchedule->message->telegram_bot_id);
    }
}
