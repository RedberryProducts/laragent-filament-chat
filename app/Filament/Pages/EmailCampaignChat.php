<?php

namespace App\Filament\Pages;

use App\Filament\Pages\AiAgentPage;
use App\AiAgents\EmailCampaignAgent;

class EmailCampaignChat extends AiAgentPage
{
    // Plug your agent here
    protected ?string $agent = EmailCampaignAgent::class;

    // Filament Settings
    public static ?string $slug = 'email-campaign';
    public static ?string $title = 'Email Campaign';
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Email Campaign';

    
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
