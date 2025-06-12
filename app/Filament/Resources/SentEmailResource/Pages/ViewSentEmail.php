<?php

namespace App\Filament\Resources\SentEmailResource\Pages;

use App\Filament\Resources\SentEmailResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;

class ViewSentEmail extends ViewRecord
{
    protected static string $resource = SentEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for view page
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Email Details')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('employee.name')
                            ->label('Employee')
                            ->disabled(),
                        \Filament\Forms\Components\TextInput::make('subject')
                            ->disabled(),
                        \Filament\Forms\Components\Textarea::make('content')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(10),
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
            ]);
    }
}
