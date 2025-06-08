<?php

namespace App\Filament\Pages;

use App\Filament\Pages\AiAgentPage;
use App\AiAgents\UserManager;

class UserManagerAgent extends AiAgentPage
{
    // Plug your agent here
    protected ?string $agent = UserManager::class;

    // Filament Settings
    public static ?string $slug = 'user-manager';
    public static ?string $title = 'User Manager';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'User Manager';

    
    // Register in navigation
    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }
    
    public static function getNavigationBadge(): ?string
    {
        return 'New';
    }
}
