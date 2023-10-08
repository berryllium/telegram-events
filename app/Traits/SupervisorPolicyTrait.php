<?php

namespace App\Traits;

use App\Models\User;

trait SupervisorPolicyTrait
{
    /**
     * @return bool|void
     */
    public function before(?User $user, $ability) {
        if($user?->hasRole('supervisor')) {
            return true;
        }
    }
}