<?php

namespace App\Policies;

use App\Models\TelegramChannel;
use App\Models\User;
use App\Traits\SupervisorPolicyTrait;
use Illuminate\Auth\Access\Response;

class TelegramChannelPolicy
{
    use SupervisorPolicyTrait;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TelegramChannel $telegramChannel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TelegramChannel $telegramChannel): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($telegramChannel->telegram_bot_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TelegramChannel $telegramChannel): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($telegramChannel->telegram_bot_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TelegramChannel $telegramChannel): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($telegramChannel->telegram_bot_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TelegramChannel $telegramChannel): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($telegramChannel->telegram_bot_id);
    }
}
