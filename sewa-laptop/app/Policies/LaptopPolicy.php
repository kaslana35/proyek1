<?php

namespace App\Policies;

use App\Models\Laptop;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaptopPolicy
{
    public function update(User $user, Laptop $laptop): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Laptop $laptop): bool
    {
        return $user->hasRole('admin');
    }
}
