<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SentEmailResource\Pages;
use App\Filament\Resources\SentEmailResource\RelationManagers;
use App\Models\SentEmail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SentEmailResource extends Resource
{
    protected static ?string $model = SentEmail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    
    protected static ?string $modelLabel = 'Sent Email';
    protected static ?string $navigationLabel = 'Sent Emails';
    protected static ?string $navigationGroup = 'Email Management';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    // Form method removed as we're using view page for displaying email details

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'sent' => 'Sent',
                        'delivered' => 'Delivered',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('employee')
                    ->relationship('employee', 'email'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // No relations needed for now
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSentEmails::route('/'),
            'view' => Pages\ViewSentEmail::route('/{record}'),
            // Removed create and edit pages as per requirements
        ];
    }
}
