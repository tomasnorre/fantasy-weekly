<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Filament\Roles;
use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class TeamResource extends Resource
{
    public static $icon = 'heroicon-o-collection';

    public static function form(Form $form)
    {
        return $form
            ->schema([
                Components\BelongsToSelect::make('identifier')
                    ->required(),
                Components\TextInput::make('captain'),
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('identifier'),
                Columns\Text::make('captain'),
            ])
            ->filters([
                //
            ]);
    }

    public static function relations()
    {
        return [
            //
        ];
    }

    public static function routes()
    {
        return [
            Pages\ListTeams::routeTo('/', 'index'),
            Pages\CreateTeam::routeTo('/create', 'create'),
            Pages\EditTeam::routeTo('/{record}/edit', 'edit'),
        ];
    }
}
