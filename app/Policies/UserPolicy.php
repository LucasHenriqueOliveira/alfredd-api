<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    public function create(User $user)
    {
        return ($user->profile_id===1); // admin
    }

    public function view(User $user)
    {
        return ($user->profile_id===1); // admin
    }
}