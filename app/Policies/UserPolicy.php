<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    public function before(User $user, $ability)
    {
        return $user->profile_id == 1; // admin
    }

    public function create(User $user)
    {
        return ($user->profile_id===1); // admin
    }

    public function view(User $user)
    {
        return ($user->profile_id===1); // admin
    }
}