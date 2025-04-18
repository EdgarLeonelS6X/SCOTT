<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $authUser, User $targetUser): bool
    {
        return true;
    }

    public function update(User $authUser, User $targetUser): bool
    {
        if ($authUser->hasRole('master')) {
            return true;
        }

        return $authUser->hasRole('admin') &&
            $targetUser->hasRole('user') &&
            $authUser->id !== $targetUser->id;
    }
}
