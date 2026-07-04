<?php

namespace App\Policies;

use App\Models\Education;
use App\Models\User;

class EducationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Education $education): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Education $education): bool
    {
        return $user->isAdmin();
    }
}
