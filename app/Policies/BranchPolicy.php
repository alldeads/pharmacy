<?php

namespace App\Policies;

use App\Models\User;

class BranchPolicy
{
    public function view(User $user)
    {
        return $user->hasPermissionTo('view Branch');
    }

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view Branch');
    }
}
