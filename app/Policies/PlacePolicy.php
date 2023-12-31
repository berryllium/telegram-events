<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\TelegramBot;
use App\Models\User;
use App\Traits\SupervisorPolicyTrait;
use Illuminate\Auth\Access\Response;

class PlacePolicy extends AbstractModelPolicy
{
    use SupervisorPolicyTrait;

    public function getBot() : ?TelegramBot {
        $place = request()->route('place');
        if(!$place) return null;
        return $place->telegram_bot;
    }

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
    public function view(User $user, Place $place): bool
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
    public function update(User $user, Place $place): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($place->telegram_bot_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Place $place): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($place->telegram_bot_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Place $place): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($place->telegram_bot_id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Place $place): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->contains($place->telegram_bot_id);
    }
}
