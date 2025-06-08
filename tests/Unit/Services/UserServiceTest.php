<?php

namespace Tests\Unit\Services;

use App\Enums\SubscriptionType;
use App\Models\User;
use App\Services\UserService\UserService;
use App\Services\UserService\UserServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserServiceInterface $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserServiceInterface::class);
    }

    public function test_count_users()
    {
        // Create test users with different subscription types
        User::factory()->count(3)->create(['subscription_type' => SubscriptionType::FREE->value]);
        User::factory()->count(2)->create(['subscription_type' => SubscriptionType::PREMIUM->value]);

        $this->assertEquals(5, $this->userService->countUsers());
        $this->assertEquals(3, $this->userService->countUsers(SubscriptionType::FREE->value));
        $this->assertEquals(2, $this->userService->countUsers(SubscriptionType::PREMIUM->value));
    }

    public function test_find_user()
    {
        $user = User::factory()->create();

        $foundById = $this->userService->findUser($user->id);
        $foundByEmail = $this->userService->findUser($user->email);

        $this->assertNotNull($foundById);
        $this->assertNotNull($foundByEmail);
        $this->assertEquals($user->id, $foundById->id);
        $this->assertEquals($user->id, $foundByEmail->id);
    }

    public function test_search_users()
    {
        $user1 = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        $user2 = User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane.smith@example.com']);

        $results = $this->userService->searchUsers('john');
        $this->assertCount(1, $results);
        $this->assertEquals($user1->id, $results->first()->id);

        $results = $this->userService->searchUsers('example.com');
        $this->assertCount(2, $results);
    }

    public function test_activate_deactivate_user()
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->assertTrue($this->userService->activateUser($user->id));
        $this->assertTrue($user->fresh()->is_active);

        $this->assertTrue($this->userService->deactivateUser($user->email));
        $this->assertFalse($user->fresh()->is_active);
    }

    public function test_change_subscription()
    {
        $user = User::factory()->create(['subscription_type' => SubscriptionType::FREE->value]);

        $this->assertTrue($this->userService->changeSubscription($user->id, SubscriptionType::PREMIUM));
        $this->assertEquals(SubscriptionType::PREMIUM, $user->fresh()->subscription_type);
    }

    public function test_reset_password()
    {
        $user = User::factory()->create();
        $newPassword = 'new-secure-password';

        $this->assertTrue($this->userService->resetPassword($user->id, $newPassword));
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }
}
