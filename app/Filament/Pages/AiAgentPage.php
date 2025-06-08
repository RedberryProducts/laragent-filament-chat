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

class AiAgentPage extends Page
{

    protected static string $view = 'filament.pages.agent';
    public static ?string $slug = 'ai-agent';
    public static ?string $title = 'Custom Page Title';
    // Navigation Settings
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Agent';
    protected static ?string $navigationGroup = 'AI Agents';
    protected static ?int $navigationSort = 2;

    protected ?string $agent = null;

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }

    public ?array $chatHistory = [
        [
            'role' => 'system',
            'content' => 'Name yourself on every response, for example: \'I, Model [X] created by [Y], sending respond: [response]\''
        ]
    ];
    public ?string $message = '';

    public function mount(): void
    {
        $this->form->fill();
        $this->setHistoryFromAgent();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Chat')
                    ->schema([
                        Textarea::make('message')
                            ->label('Message')
                            ->placeholder('Type your message here...')
                            ->rows(2)
                            ->required(),
                    ])
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('send')
                ->label('Send')
                ->action('sendMessage')
                ->color('primary'),
            Action::make('clear')
                ->label('Clear History')
                ->action('clearHistory')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    public function sendMessage(): void
    {
        $this->validateOnly('message');
        // Add user message to chat history
        $response = $this->getAgentInstance()->respond($this->message);

        $this->setHistoryFromAgent();

        $this->reset('message');
    }

    public function clearHistory(): void
    {
        $this->getAgentInstance()->clear();

        $this->setHistoryFromAgent();
        
        Notification::make()
            ->title('Chat history cleared')
            ->success()
            ->send();
    }

    public function getChatHistory(): array
    {
        return array_filter($this->chatHistory, fn($message) => in_array($message['role'], ['user', 'assistant']));
    }

    public function getSystemMessages(): array
    {
        return array_filter($this->chatHistory, fn($message) => !in_array($message['role'], ['user', 'assistant']));
    }

    protected function getAgentInstance(): Agent
    {
        return $this->agent::forUser(auth()->user());
    }

    protected function setHistoryFromAgent(): void
    {
        $this->chatHistory = $this->getAgentInstance()->chatHistory()->toArray();
    }
}
