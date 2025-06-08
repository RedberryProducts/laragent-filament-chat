<?php

namespace App\AiAgents;

use LarAgent\Agent;
use LarAgent\Attributes\Tool;
use App\Services\UserService\UserService;
use App\Enums\SubscriptionType;
use Illuminate\Support\Str;

class UserManager extends Agent
{
    protected $model = 'gpt-4o-mini';

    protected $history = 'session';

    protected $provider = 'default';

    protected $tools = [];

    protected $isAdmin = false;

    protected UserService $userService;

    public function __construct($key)
    {
        parent::__construct($key);
        $this->userService = new UserService();
    }

    public function instructions()
    {
        $tools = $this->getTools();
        return view('prompts.user_manager.instructions', compact('tools'));
    }

    public function isAdmin(bool $isAdmin = true)
    {
        $this->isAdmin = $isAdmin;
    }

    public function prompt($message)
    {

        return $message;
    }

    #[Tool("Returns a amount of all users")]
    public function countUsers()
    {
        return $this->userService->countUsers();
    }

    #[Tool("Find the user data base on the identifier", [
        'identifier' => 'User ID or the email (Prefer ID if possible)'
    ])]
    public function findUser(string $identifier)
    {
        return $this->userService->findUser($identifier);
    }

    #[Tool("Change the subscription type of the user", [
        'identifier' => 'User ID or the email (Prefer ID if possible)',
        'subscriptionType' => 'Subscription type'
    ])]
    public function changeSubscription(string $identifier, SubscriptionType $subscriptionType)
    {
        return $this->userService->changeSubscription($identifier, $subscriptionType);
    }

    #[Tool("Search for users by name or email", [
        'query' => 'Search term to look for in name or email',
        'perPage' => 'Number of results per page (default: 15)'
    ])]
    public function searchUsers(string $query, int $perPage = 15)
    {
        return $this->userService->searchUsers($query, $perPage);
    }

    #[Tool("Activate a user account", [
        'identifier' => 'User ID or email of the account to activate'
    ])]
    public function activateUser(string $identifier): bool
    {
        return $this->userService->activateUser($identifier);
    }

    #[Tool("Deactivate a user account", [
        'identifier' => 'User ID or email of the account to deactivate'
    ])]
    public function deactivateUser(string $identifier): bool
    {
        return $this->userService->deactivateUser($identifier);
    }

    #[Tool("Reset a user's password", [
        'identifier' => 'User ID or email of the account'
    ])]
    public function resetPassword(string $identifier): string
    {
        $newPassword = Str::random(12);
        $this->userService->resetPassword($identifier, $newPassword);
        return "Password reset successfully. 
            Recommend user to change password after the first login. 
            New password: $newPassword";
    }
}
