<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->email !== 'scott@stargroup.com.mx') {
            $user->assignRole('user');
        }
    }
}
