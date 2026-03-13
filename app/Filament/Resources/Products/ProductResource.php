<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\AttributeFamily;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Grid;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $pluralModelLabel = 'Products';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('slug', Str::slug($state));
                        }),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->readOnly(),

                    TextInput::make('sku')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Select::make('brand_id')
                        ->label('Brand')
                        ->options(Brand::query()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Select::make('category_id')
                        ->label('Category')
                        ->options(Category::query()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Select::make('attribute_family_id')
                        ->label('Attribute Family')
                        ->options(AttributeFamily::query()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->nullable(),
                ])
                ->columns(2),

            Section::make('Descriptions')
                ->schema([
                    Textarea::make('description')
                        ->rows(6)
                        ->columnSpanFull(),
                ])
                ->columns(1),

            Section::make('Pricing & Inventory')
                ->schema([
                    TextInput::make('price')
                        ->numeric()
                        ->required()
                        ->default(0),

                    TextInput::make('special_price')
                        ->numeric()
                        ->nullable(),

                    TextInput::make('stock')
                        ->numeric()
                        ->integer()
                        ->default(0)
                        ->required(),
                ])
                ->columns(3),
            Section::make('Media & Status')
                ->schema([
                    FileUpload::make('image')
                        ->label('Main Image')
                        ->image()
                        ->disk('public')
                        ->directory('products')
                        ->visibility('public')
                        ->imageEditor()
                        ->nullable(),

                    Repeater::make('images')
                        ->relationship('images')
                        ->label('Gallery Images')
                        ->schema([
                            Grid::make(2)->schema([
                                FileUpload::make('path')
                                    ->label('Gallery Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products')
                                    ->visibility('public')
                                    ->imageEditor()
                                    ->required(),

                                TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                            ]),
                        ])
                        ->defaultItems(0)
                        ->addActionLabel('Add Gallery Image')
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(function (array $state): ?string {
                            return filled($state['path'] ?? null)
                                ? 'Gallery Image'
                                : 'New Image';
                        })
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->default(true),

                    Toggle::make('is_featured')
                        ->default(false),
                ])
                ->columns(1),
            Section::make('Attribute Values')
                ->schema([
                    Repeater::make('attributeValues')
                        ->relationship('attributeValues')
                        ->schema([
                            Select::make('attribute_id')
                                ->label('Attribute')
                                ->options(\App\Models\ProductAttribute::query()->pluck('name', 'id')->toArray())
                                ->searchable()
                                ->preload()
                                ->required(),

                            TextInput::make('value')
                                ->label('Value')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->addActionLabel('Add Attribute Value')
                        ->columnSpanFull(),
                ])
                ->columns(1),
            Section::make('SEO')
                ->schema([

                    TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->maxLength(255),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->rows(3),

                    TextInput::make('meta_keywords')
                        ->label('Meta Keywords')
                        ->helperText('Comma separated keywords'),

                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('attributeFamily.name')
                    ->label('Attribute Family')
                    ->sortable(),

                TextColumn::make('price')
                    ->money('KES')
                    ->sortable(),

                TextColumn::make('stock')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}