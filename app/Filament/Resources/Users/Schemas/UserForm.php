<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('users.user.name'))
                    ->validationAttribute(__('users.user.name'))
                    ->minLength(3)
                    ->required(),
                TextInput::make('email')
                    ->label(__('users.user.email'))
                    ->validationAttribute(__('validation.attributes.email'))
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                // Using Select Component
                Select::make('roles')
                    ->label(__('users.user.role'))
                    ->validationAttribute(__('users.user.role'))
                    ->relationship('roles', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->translated_name)
                    ->preload()
                    ->required(),
                TextInput::make('password')
                    ->label(__('users.user.password'))
                    ->validationAttribute(__('users.user.password'))
                    ->visible(fn (string $context) => $context === 'create')
                    ->revealable()
                    ->password()
                    ->required(),
            ]);
    }
}
