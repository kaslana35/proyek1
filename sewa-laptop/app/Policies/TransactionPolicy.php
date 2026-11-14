<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $transaction->user_id === $user->id;
    }

    public function update(User $user, Transaction $transaction): bool
    {
        return $user->hasRole('admin');
    }

    public function cancel(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $transaction->user_id === $user->id && in_array($transaction->status, ['pending', 'approved']);
    }
}
