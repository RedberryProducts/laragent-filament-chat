<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SentEmailsRelationManager extends RelationManager
{
    protected static string $relationship = 'sentEmails';

    protected static ?string $title = 'Sent Emails';

    protected static ?string $modelLabel = 'sent email';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn (\App\Models\SentEmail $record): string => $record->subject),
                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'info',
                        'delivered' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // No create action as emails are sent programmatically
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (\App\Models\SentEmail $record): string => 
                        \App\Filament\Resources\SentEmailResource::getUrl('view', ['record' => $record])
                    ),
            ])
            ->bulkActions([
                // No bulk actions
            ]);
    }
}
