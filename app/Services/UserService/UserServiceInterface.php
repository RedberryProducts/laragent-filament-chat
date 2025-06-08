<?php

namespace App\Services\UserService;

use App\Enums\SubscriptionType;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    /**
     * Count users, optionally filtered by subscription type
     */
    public function countUsers(?string $subscriptionType = null): int;

    /**
     * Find a user by ID or email
     */
    public function findUser(string|int $identifier): ?User;

    /**
     * Search users by name or email
     */
    public function searchUsers(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Activate a user by ID or email
     */
    public function activateUser(string|int $identifier): bool;

    /**
     * Deactivate a user by ID or email
     */
    public function deactivateUser(string|int $identifier): bool;

    /**
     * Change a user's subscription type
     * @param string|int $identifier User ID or email
     * @param string|\App\Enums\SubscriptionType $subscriptionType Subscription type as string or enum
     */
    public function changeSubscription(string|int $identifier, \App\Enums\SubscriptionType $subscriptionType): bool;

    /**
     * Reset a user's password
     */
    public function resetPassword(string|int $identifier, string $newPassword): bool;
}
