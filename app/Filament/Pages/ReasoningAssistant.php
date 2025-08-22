<?php

namespace App\Filament\Pages;

use App\Filament\Pages\AiAgentPage;
use App\AiAgents\ReasoningAgent;

class ReasoningAssistant extends AiAgentPage
{
    // Plug your agent here
    protected ?string $agent = ReasoningAgent::class;

    // Filament Settings
    public static ?string $slug = 'reasoning-assistant';
    public static ?string $title = 'Reasoning Assistant';
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Reasoning Assistant';

    
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
