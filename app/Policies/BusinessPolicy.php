<?php

namespace App\Policies;

use App\Models\Business\Business;
use App\Models\User;

class BusinessPolicy
{
    public function manage(User $user, Business $business): bool
    {
        return $user->id === $business->user_id || $user->hasAnyRole(['admin', 'super_admin']);
    }
}
