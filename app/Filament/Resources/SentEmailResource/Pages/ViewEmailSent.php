<?php

namespace App\Filament\Resources\SentEmailResource\Pages;

use App\Filament\Resources\SentEmailResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSentEmail extends ViewRecord
{
    protected static string $resource = SentEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for view page
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Section::make('Email Details')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('employee.name')
                        ->label('Employee')
                        ->disabled(),
                    \Filament\Forms\Components\TextInput::make('subject')
                        ->disabled(),
                    \Filament\Forms\Components\RichEditor::make('content')
                        ->disabled()
                        ->columnSpanFull(),
                    \Filament\Forms\Components\DateTimePicker::make('sent_at')
                        ->disabled(),
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'sent' => 'Sent',
                            'delivered' => 'Delivered',
                            'failed' => 'Failed',
                        ])
                        ->disabled(),
                ]),
        ];
    }
}
