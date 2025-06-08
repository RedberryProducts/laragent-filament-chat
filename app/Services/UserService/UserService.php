<?php

namespace App\Services\UserService;

use App\Enums\SubscriptionType;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService implements UserServiceInterface
{
    public function countUsers(?string $subscriptionType = null): int
    {
        $query = User::query();

        if ($subscriptionType && in_array($subscriptionType, SubscriptionType::values())) {
            $query->where('subscription_type', $subscriptionType);
        }

        return $query->count();
    }

    public function findUser(string|int $identifier): ?User
    {
        if (is_numeric($identifier)) {
            return User::find($identifier);
        }

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $identifier)->first();
        }

        return null;
    }

    public function searchUsers(string $query, int $perPage = 15): LengthAwarePaginator
    {
        $searchTerm = '%' . $query . '%';
        
        return User::where('name', 'like', $searchTerm)
            ->orWhere('email', 'like', $searchTerm)
            ->paginate($perPage);
    }

    public function activateUser(string|int $identifier): bool
    {
        $user = $this->findUser($identifier);
        
        if (!$user) {
            return false;
        }
        
        return $user->update(['is_active' => true]);
    }

    public function deactivateUser(string|int $identifier): bool
    {
        $user = $this->findUser($identifier);
        
        if (!$user) {
            return false;
        }

        return $user->update(['is_active' => false]);
    }

    public function changeSubscription(string|int $identifier, \App\Enums\SubscriptionType $subscriptionType): bool
    {
        $user = $this->findUser($identifier);
        
        if (!$user) {
            return false;
        }

        return $user->update(['subscription_type' => $subscriptionType]);
    }

    public function resetPassword(string|int $identifier, string $newPassword): bool
    {
        $user = $this->findUser($identifier);
        
        if (!$user) {
            return false;
        }

        return $user->update([
            'password' => Hash::make($newPassword),
            'remember_token' => Str::random(60),
        ]);
    }
}
