<?php

namespace App\Policies;

use App\Models\User;

class ProfilePolicy
{

    public function create(User $user)
    {
        return ($user->profile_id===1); // admin
    }

    public function update(User $user)
    {
        return ($user->profile_id===1); // admin
    }

    public function delete(User $user)
    {
        return ($user->profile_id===1); // admin
    }

    public function view(User $user)
    {
        return ($user->profile_id===1); // admin
    }
}