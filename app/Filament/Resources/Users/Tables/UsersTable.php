<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('users.user.name'))
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('users.user.email'))
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label(__('users.user.role'))
                    ->badge()
                    ->formatStateUsing(function ($state, $record) {
                        $locale = app()->getLocale();
                        
                        // Get the role
                        $role = $record->roles->first();
                        
                        if (!$role) {
                            return '-';
                        }
                        
                        // Get translations
                        $translations = is_string($role->translations) 
                            ? json_decode($role->translations, true) 
                            : $role->translations;
                        
                        // Return translated name or fallback
                        return $translations[$locale] 
                            ?? $translations['en'] 
                            ?? $role->name;
                    })
                    ->color(fn ($record) => match($record->roles->first()?->name) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        'user' => 'success',
                        default => 'primary',
                    }),
                
                TextColumn::make('created_at')
                    ->label(__('users.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('users.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
