<?php

namespace App\Filament\Resources\ProductAttributes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug'),
                TextInput::make('type')
                    ->required()
                    ->default('text'),
                TextInput::make('unit'),
                Textarea::make('options')
                    ->columnSpanFull(),
            ]);
    }
}
