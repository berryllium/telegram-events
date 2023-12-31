<?php

namespace App\Policies;

use App\Models\Field;
use App\Models\User;
use App\Traits\SupervisorPolicyTrait;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Collection;

class FieldPolicy extends AbstractModelPolicy
{
    use SupervisorPolicyTrait;

    public function getBot()
    {
        $field = request()->route('field');
        if(!$field) return null;
        $bots = $field->form->bots;
        $matchedBotPos = $bots->pluck('id')->search(session('bot'));
        return $matchedBotPos !== false ? null : $bots->first();
    }

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
    public function view(User $user, Field $field): bool
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
    public function update(User $user, Field $field): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->intersect($field->form->bots);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Field $field): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->intersect($field->form->bots);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Field $field): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->intersect($field->form->bots);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Field $field): bool
    {
        return $user->hasRole('admin') && $user->telegram_bots->intersect($field->form->bots);
    }
}
