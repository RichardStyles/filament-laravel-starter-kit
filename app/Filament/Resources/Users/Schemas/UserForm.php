<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8),
                Toggle::make('email_verified')
                    ->label('Email verified')
                    ->dehydrated(false)
                    ->afterStateHydrated(fn (Toggle $component, $state, $record): Toggle => $component->state((bool) $record?->email_verified_at))
                    ->afterStateUpdated(function ($state, $record): void {
                        if ($record === null) {
                            return;
                        }
                        $record->forceFill(['email_verified_at' => $state ? now() : null])->save();
                    }),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->options(fn () => Role::query()->pluck('name', 'name'))
                    ->multiple()
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }
}
