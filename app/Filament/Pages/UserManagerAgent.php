<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use LarAgent\Agent;
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
