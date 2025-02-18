<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === 'admin' || $user->role === 'super-admin';
    }

    public function view(User $user, User $model)
    {
        return $user->id === $model->id || $this->viewAny($user);
    }

    public function create(User $user)
    {
        return $user->role === 'super-admin';
    }

    public function update(User $user, User $model)
    {
        return $user->id === $model->id || $user->role === 'super-admin';
    }

    public function delete(User $user, User $model)
    {
        return $user->role === 'super-admin' && $user->id !== $model->id;
    }
} 