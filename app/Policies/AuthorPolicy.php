<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\TelegramBot;
use App\Models\TelegramBotAuthor;
use App\Models\User;
use App\Traits\SupervisorPolicyTrait;

class AuthorPolicy extends AbstractModelPolicy
{
    use SupervisorPolicyTrait;

    public function getBot() : ?TelegramBot {
        return null;
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
    public function view(User $user, Author $author): bool
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
    public function update(User $user, Author $author): bool
    {
        return TelegramBotAuthor::query()
            ->whereIn('telegram_bot_id', $user->telegram_bots->pluck('id')->toArray())->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Author $author): bool
    {
        return TelegramBotAuthor::query()
            ->whereIn('telegram_bot_id', $user->telegram_bots->pluck('id')->toArray())->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Author $author): bool
    {
        return TelegramBotAuthor::query()
            ->whereIn('telegram_bot_id', $user->telegram_bots->pluck('id')->toArray())->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Author $author): bool
    {
        return TelegramBotAuthor::query()
            ->whereIn('telegram_bot_id', $user->telegram_bots->pluck('id')->toArray())->exists();
    }
}
