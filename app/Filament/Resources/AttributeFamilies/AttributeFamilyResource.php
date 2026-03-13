<?php

namespace App\Filament\Resources\AttributeFamilies;

use App\Filament\Resources\AttributeFamilies\Pages\CreateAttributeFamily;
use App\Filament\Resources\AttributeFamilies\Pages\EditAttributeFamily;
use App\Filament\Resources\AttributeFamilies\Pages\ListAttributeFamilies;
use App\Models\AttributeFamily;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributeFamilyResource extends Resource
{
    protected static ?string $model = AttributeFamily::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    if (blank($get('slug'))) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }

                    if (blank($get('code'))) {
                        $set('code', \Illuminate\Support\Str::snake($state));
                    }
                }),

            TextInput::make('slug')
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(255),

            TextInput::make('code')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->helperText('Stable internal key, e.g. brake_disc'),

            Textarea::make('notes')
                ->rows(4),

            CheckboxList::make('attributes')
                ->relationship('attributes', 'name')
                ->columns(2)
                ->searchable()
                ->bulkToggleable()
                ->helperText('Select the attributes that belong to this family'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('attributes_count')
                    ->counts('attributes')
                    ->label('Attributes'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttributeFamilies::route('/'),
            'create' => CreateAttributeFamily::route('/create'),
            'edit' => EditAttributeFamily::route('/{record}/edit'),
        ];
    }
}