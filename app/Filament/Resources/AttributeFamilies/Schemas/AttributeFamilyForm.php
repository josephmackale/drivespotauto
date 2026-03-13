<?php

namespace App\Filament\Resources\AttributeFamilies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttributeFamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug'),
                TextInput::make('code'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
