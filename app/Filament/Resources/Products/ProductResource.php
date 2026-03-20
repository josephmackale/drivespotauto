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
use App\Models\VsEngine;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\CheckboxList;
use Filament\Actions\Action;
use App\Models\ProductAttribute;

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
            Grid::make(12)
                ->columnSpanFull()
                ->schema([
                    Grid::make(1)
                        ->schema([
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

                                    Select::make('attribute_family_id')
                                        ->label('Attribute Family')
                                        ->options(AttributeFamily::query()->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),
                                ])
                                ->columns(1),

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
                                ->columns(1),

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
                                    Grid::make(12)
                                        ->schema([
                                            Select::make('pending_attribute_id')
                                                ->label('Attribute')
                                                ->placeholder('Search attribute...')
                                                ->searchable()
                                                ->preload()
                                                ->dehydrated(false)
                                                ->options(fn () => \App\Models\ProductAttribute::query()
                                                    ->orderBy('name')
                                                    ->pluck('name', 'id')
                                                    ->toArray())
                                                ->columnSpan(5),

                                            TextInput::make('pending_attribute_value')
                                                ->label('Value')
                                                ->placeholder('Enter attribute value')
                                                ->dehydrated(false)
                                                ->columnSpan(5),

                                            Placeholder::make('add_attribute_button')
                                                ->label(' ')
                                                ->content(new HtmlString('
                                                    <div style="height:40px;display:flex;align-items:end;">
                                                        <span style="
                                                            display:inline-flex;
                                                            align-items:center;
                                                            justify-content:center;
                                                            height:40px;
                                                            padding:0 14px;
                                                            border-radius:10px;
                                                            background:#2563eb;
                                                            color:white;
                                                            font-weight:600;
                                                            font-size:14px;
                                                        ">
                                                            Use Add button below
                                                        </span>
                                                    </div>
                                                '))
                                                ->visible(fn ($get) => false)
                                                ->columnSpan(2),
                                        ]),

                                    Repeater::make('attributeValues')
                                        ->relationship('attributeValues')
                                        ->label('')
                                        ->defaultItems(0)
                                        ->addable(false)
                                        ->reorderable(false)
                                        ->collapsible()
                                        ->collapsed()
                                        ->cloneable(false)
                                        ->itemLabel(function (array $state): string {
                                            $attributeName = null;

                                            if (! empty($state['attribute_id'])) {
                                                $attributeName = \App\Models\ProductAttribute::query()
                                                    ->whereKey($state['attribute_id'])
                                                    ->value('name');
                                            }

                                            $value = trim((string) ($state['value'] ?? ''));

                                            if ($attributeName && $value !== '') {
                                                return "{$attributeName}: {$value}";
                                            }

                                            if ($attributeName) {
                                                return $attributeName;
                                            }

                                            if ($value !== '') {
                                                return $value;
                                            }

                                            return 'New attribute';
                                        })
                                        ->schema([
                                            Grid::make(12)
                                                ->schema([
                                                    Select::make('attribute_id')
                                                        ->label('Attribute')
                                                        ->searchable()
                                                        ->preload()
                                                        ->required()
                                                        ->options(fn () => \App\Models\ProductAttribute::query()
                                                            ->orderBy('name')
                                                            ->pluck('name', 'id')
                                                            ->toArray())
                                                        ->columnSpan(5),

                                                    TextInput::make('value')
                                                        ->label('Value')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->columnSpan(7),
                                                ]),
                                        ])
                                        ->columns(1)
                                        ->columnSpanFull()
                                        ->deleteAction(
                                            fn (\Filament\Actions\Action $action) => $action->icon('heroicon-o-trash')
                                        ),

                                    Placeholder::make('attribute_values_summary')
                                        ->label('Current attributes')
                                        ->content(function ($get) {
                                            $rows = $get('attributeValues') ?? [];

                                            if (empty($rows)) {
                                                return new HtmlString('<div style="font-size:14px;color:#6b7280;">No attributes added yet.</div>');
                                            }

                                            $attributeMap = \App\Models\ProductAttribute::query()
                                                ->pluck('name', 'id')
                                                ->toArray();

                                            $html = '<div style="display:flex;flex-direction:column;gap:8px;">';

                                            foreach ($rows as $row) {
                                                $name = $attributeMap[$row['attribute_id'] ?? null] ?? 'Unknown attribute';
                                                $value = e($row['value'] ?? '');

                                                $html .= '
                                                    <div style="
                                                        display:flex;
                                                        align-items:center;
                                                        gap:10px;
                                                        padding:10px 12px;
                                                        border:1px solid #e5e7eb;
                                                        border-radius:10px;
                                                        background:#f9fafb;
                                                        font-size:14px;
                                                    ">
                                                        <span style="font-weight:600;color:#111827;">' . e($name) . ':</span>
                                                        <span style="color:#374151;">' . $value . '</span>
                                                    </div>
                                                ';
                                            }

                                            $html .= '</div>';

                                            return new HtmlString($html);
                                        })
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                            Section::make('Compatibility')
                                ->schema([

                                    Hidden::make('selected_engine_ids')
                                        ->default(fn (?Product $record) => $record?->engines()->pluck('vs_engines.id')->toArray() ?? [])
                                        ->dehydrated(false),

                                    Hidden::make('filteredCompatibilityOptions')
                                        ->default([])
                                        ->dehydrated(false),

                                    Hidden::make('compatibility_results_collapsed')
                                        ->default(false)
                                        ->dehydrated(false),

                                    TextInput::make('compatibility_search')
                                        ->label('Search vehicle')
                                        ->placeholder('Search make, model, generation, engine code, fuel, year...')
                                        ->live(debounce: 400)
                                        ->dehydrated(false)
                                        ->afterStateUpdated(function ($set) {
                                            $set('compatibility_results', []);
                                            $set('compatibility_results_collapsed', false);
                                        }),

                                    Placeholder::make('attach_selected_top')
                                        ->label('')
                                        ->content(function ($get) {
                                            $checked = $get('compatibility_results') ?? [];
                                            $attached = $get('selected_engine_ids') ?? [];
                                            $search = trim((string) ($get('compatibility_search') ?? ''));
                                            $collapsed = (bool) ($get('compatibility_results_collapsed') ?? false);

                                            if ($search === '') {
                                                return new HtmlString('<div style="font-size:14px;color:#6b7280;">Type a search term to find matching vehicles.</div>');
                                            }

                                            return new HtmlString('
                                                <div style="
                                                    display:flex;
                                                    flex-wrap:wrap;
                                                    align-items:center;
                                                    justify-content:space-between;
                                                    gap:12px;
                                                    padding:12px;
                                                    border:1px solid #e5e7eb;
                                                    border-radius:12px;
                                                    background:#f9fafb;
                                                ">
                                                    <div style="font-size:14px;color:#374151;display:flex;gap:14px;flex-wrap:wrap;">
                                                        <span><strong>' . count($checked) . '</strong> selected</span>
                                                        <span><strong>' . count($attached) . '</strong> attached</span>
                                                    </div>

                                                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                                        <button
                                                            type="button"
                                                            x-on:click="
                                                                $wire.set(
                                                                    \'data.compatibility_results\',
                                                                    Object.keys($wire.get(\'data.filteredCompatibilityOptions\') || {})
                                                                        .map(id => Number(id))
                                                                        .filter(id => !($wire.get(\'data.selected_engine_ids\') || []).includes(id))
                                                                )
                                                            "
                                                            style="
                                                                padding:8px 12px;
                                                                border:1px solid #d1d5db;
                                                                border-radius:10px;
                                                                background:white;
                                                                font-size:14px;
                                                                cursor:pointer;
                                                            "
                                                        >
                                                            Select All Visible
                                                        </button>

                                                        <button
                                                            type="button"
                                                            x-on:click="$wire.set(\'data.compatibility_results\', [])"
                                                            style="
                                                                padding:8px 12px;
                                                                border:1px solid #d1d5db;
                                                                border-radius:10px;
                                                                background:white;
                                                                font-size:14px;
                                                                cursor:pointer;
                                                            "
                                                        >
                                                            Clear Selection
                                                        </button>

                                                        <button
                                                            type="button"
                                                            x-on:click="
                                                                (async () => {
                                                                    const existing = $wire.get(\'data.selected_engine_ids\') || [];
                                                                    const checked = $wire.get(\'data.compatibility_results\') || [];

                                                                    if (!checked.length) return;

                                                                    const merged = [...new Set([...existing, ...checked])];

                                                                    await $wire.set(\'data.selected_engine_ids\', merged);
                                                                    await $wire.set(\'data.compatibility_results\', []);
                                                                    await $wire.set(\'data.compatibility_results_collapsed\', true);
                                                                })()
                                                            "
                                                            style="
                                                                padding:8px 14px;
                                                                border:none;
                                                                border-radius:10px;
                                                                background:#2563eb;
                                                                color:white;
                                                                font-size:14px;
                                                                font-weight:600;
                                                                cursor:pointer;
                                                            "
                                                        >
                                                            Attach Selected Vehicles
                                                        </button>

                                                        <button
                                                            type="button"
                                                            x-on:click="$wire.set(\'data.compatibility_results_collapsed\', !($wire.get(\'data.compatibility_results_collapsed\') || false))"
                                                            style="
                                                                min-width:44px;
                                                                height:40px;
                                                                padding:0 14px;
                                                                border:1px solid #d1d5db;
                                                                border-radius:10px;
                                                                background:white;
                                                                font-size:20px;
                                                                font-weight:700;
                                                                line-height:1;
                                                                cursor:pointer;
                                                            "
                                                            title="' . ($collapsed ? 'Expand matching vehicles' : 'Collapse matching vehicles') . '"
                                                        >
                                                            ' . ($collapsed ? '+' : '−') . '
                                                        </button>
                                                    </div>
                                                </div>
                                            ');
                                        })
                                        ->dehydrated(false)
                                        ->visible(fn ($get) => filled($get('compatibility_search'))),

                                    CheckboxList::make('compatibility_results')
                                        ->label('Matching Vehicles')
                                        ->options(function ($get, $set) {
                                            $search = trim((string) ($get('compatibility_search') ?? ''));
                                            $selectedIds = array_map('intval', $get('selected_engine_ids') ?? []);

                                            if ($search === '') {
                                                $set('filteredCompatibilityOptions', []);

                                                return [];
                                            }

                                            $options = VsEngine::query()
                                                ->with(['generation.model.make'])
                                                ->where(function ($query) use ($search) {
                                                    $query
                                                        ->where('engine_code', 'like', "%{$search}%")
                                                        ->orWhere('variant_name', 'like', "%{$search}%")
                                                        ->orWhere('fuel_type', 'like', "%{$search}%")
                                                        ->orWhere('year_from', 'like', "%{$search}%")
                                                        ->orWhere('year_to', 'like', "%{$search}%")
                                                        ->orWhereHas('generation', function ($q) use ($search) {
                                                            $q->where('name', 'like', "%{$search}%")
                                                                ->orWhere('code', 'like', "%{$search}%")
                                                                ->orWhereHas('model', function ($q2) use ($search) {
                                                                    $q2->where('name', 'like', "%{$search}%")
                                                                        ->orWhereHas('make', function ($q3) use ($search) {
                                                                            $q3->where('name', 'like', "%{$search}%");
                                                                        });
                                                                });
                                                        });
                                                })
                                                ->orderBy('engine_code')
                                                ->limit(30)
                                                ->get()
                                                ->mapWithKeys(function (VsEngine $record) use ($selectedIds) {
                                                    $make = $record->generation?->model?->make?->name;
                                                    $model = $record->generation?->model?->name;
                                                    $generationCode = $record->generation?->code;
                                                    $engine = $record->engine_code;
                                                    $capacity = $record->capacity_l ? $record->capacity_l . 'L' : null;

                                                    $years = ($record->year_from && $record->year_to)
                                                        ? "{$record->year_from}-{$record->year_to}"
                                                        : null;

                                                    $fuel = $record->fuel_type;

                                                    $label = collect([
                                                        $make,
                                                        $model ? "{$model} ({$generationCode})" : null,
                                                        $capacity,
                                                        $engine,
                                                        $years,
                                                        $fuel ? "({$fuel})" : null,
                                                    ])->filter()->implode(' ');

                                                    if (in_array((int) $record->id, $selectedIds, true)) {
                                                        $label = '✓ Attached — ' . $label;
                                                    }

                                                    return [$record->id => $label];
                                                })
                                                ->toArray();

                                            $set('filteredCompatibilityOptions', $options);

                                            return $options;
                                        })
                                        ->disableOptionWhen(function ($value, $label, $get) {
                                            $selectedIds = array_map('intval', $get('selected_engine_ids') ?? []);

                                            return in_array((int) $value, $selectedIds, true);
                                        })
                                        ->columns(1)
                                        ->live()
                                        ->dehydrated(false)
                                        ->visible(fn ($get) => filled($get('compatibility_search')) && ! (bool) ($get('compatibility_results_collapsed') ?? false))
                                        ->extraAttributes([
                                            'style' => '
                                                max-height: 320px;
                                                overflow-y: auto;
                                                overflow-x: hidden;
                                                padding: 12px;
                                                border: 1px solid #e5e7eb;
                                                border-radius: 12px;
                                                background: white;
                                                white-space: normal;
                                                word-break: break-word;
                                            ',
                                        ]),

                                    Placeholder::make('attached_vehicles')
                                        ->label('Attached Compatibility')
                                        ->content(function ($get) {
                                            $ids = $get('selected_engine_ids') ?? [];

                                            if (empty($ids)) {
                                                return new HtmlString('<div style="font-size:14px;color:#6b7280;">No compatible vehicles attached yet.</div>');
                                            }

                                            $records = VsEngine::query()
                                                ->with(['generation.model.make'])
                                                ->whereIn('id', $ids)
                                                ->get()
                                                ->sortBy(function ($record) {
                                                    return ($record->generation?->model?->make?->name ?? '') . ' ' .
                                                        ($record->generation?->model?->name ?? '') . ' ' .
                                                        ($record->engine_code ?? '');
                                                });

                                            $html = '<div style="display:flex;flex-direction:column;gap:8px;">';

                                            foreach ($records as $record) {
                                                $make = $record->generation?->model?->make?->name;
                                                $model = $record->generation?->model?->name;
                                                $generationCode = $record->generation?->code;
                                                $engine = $record->engine_code;
                                                $capacity = $record->capacity_l ? $record->capacity_l . "L" : null;

                                                $years = ($record->year_from && $record->year_to)
                                                    ? "{$record->year_from}-{$record->year_to}"
                                                    : null;

                                                $fuel = $record->fuel_type;

                                                $label = collect([
                                                    $make,
                                                    $model ? "{$model} ({$generationCode})" : null,
                                                    $capacity,
                                                    $engine,
                                                    $years,
                                                    $fuel ? "({$fuel})" : null,
                                                ])->filter()->implode(' ');

                                                $html .= '
                                                    <div style="
                                                        display:flex;
                                                        align-items:center;
                                                        justify-content:space-between;
                                                        gap:12px;
                                                        padding:10px 12px;
                                                        border:1px solid #e5e7eb;
                                                        border-radius:12px;
                                                        font-size:14px;
                                                        background:white;
                                                    ">
                                                        <span style="flex:1;min-width:0;">' . e($label) . '</span>

                                                        <button
                                                            type="button"
                                                            x-on:click="
                                                                (async () => {
                                                                    const current = $wire.get(\'data.selected_engine_ids\') || [];
                                                                    await $wire.set(
                                                                        \'data.selected_engine_ids\',
                                                                        current.filter(id => id != ' . $record->id . ')
                                                                    );
                                                                })()
                                                            "
                                                            style="
                                                                border:none;
                                                                background:transparent;
                                                                color:#ef4444;
                                                                font-weight:600;
                                                                cursor:pointer;
                                                                white-space:nowrap;
                                                            "
                                                        >
                                                            Remove
                                                        </button>
                                                    </div>
                                                ';
                                            }

                                            $html .= '</div>';

                                            return new HtmlString($html);
                                        })
                                        ->columnSpanFull(),

                                ])
                                ->columns(1),
                            Section::make('OE References')
                                ->schema([
                                    Select::make('oeReferences')
                                        ->label('OE / OEM References')
                                        ->relationship(
                                            name: 'oeReferences',
                                            titleAttribute: 'reference_number_raw',
                                            modifyQueryUsing: fn ($query) => $query->orderBy('brand_name_raw')->orderBy('reference_number_raw')
                                        )
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Attach existing OE references from the master list, or create a new one inline.')
                                        ->getSearchResultsUsing(function (string $search): array {
                                            return \App\Models\OeReference::query()
                                                ->where(function ($query) use ($search) {
                                                    $query->where('reference_number_raw', 'like', "%{$search}%")
                                                        ->orWhere('reference_number_normalized', 'like', "%{$search}%")
                                                        ->orWhere('brand_name_raw', 'like', "%{$search}%")
                                                        ->orWhere('brand_name_normalized', 'like', "%{$search}%")
                                                        ->orWhere('reference_type', 'like', "%{$search}%");
                                                })
                                                ->orderBy('brand_name_raw')
                                                ->orderBy('reference_number_raw')
                                                ->limit(50)
                                                ->get()
                                                ->mapWithKeys(function ($record) {
                                                    $brand = filled($record->brand_name_raw) ? $record->brand_name_raw : 'Unknown Brand';
                                                    $label = "{$brand} — {$record->reference_number_raw}";

                                                    if (!empty($record->reference_type)) {
                                                        $label .= " ({$record->reference_type})";
                                                    }

                                                    return [$record->id => $label];
                                                })
                                                ->toArray();
                                        })
                                        ->getOptionLabelsUsing(function (array $values): array {
                                            return \App\Models\OeReference::query()
                                                ->whereIn('id', $values)
                                                ->get()
                                                ->mapWithKeys(function ($record) {
                                                    $brand = filled($record->brand_name_raw) ? $record->brand_name_raw : 'Unknown Brand';
                                                    $label = "{$brand} — {$record->reference_number_raw}";

                                                    if (!empty($record->reference_type)) {
                                                        $label .= " ({$record->reference_type})";
                                                    }

                                                    return [$record->id => $label];
                                                })
                                                ->toArray();
                                        })
                                        ->createOptionForm([
                                            Grid::make(3)->schema([
                                                TextInput::make('brand_name_raw')
                                                    ->label('Brand / Manufacturer')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $normalized = \Illuminate\Support\Str::of((string) $state)
                                                            ->upper()
                                                            ->ascii()
                                                            ->replaceMatches('/[^A-Z0-9]+/', ' ')
                                                            ->trim()
                                                            ->value();

                                                        $set('brand_name_normalized', $normalized);
                                                    }),

                                                TextInput::make('reference_number_raw')
                                                    ->label('Reference Number')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $normalized = \Illuminate\Support\Str::of((string) $state)
                                                            ->upper()
                                                            ->replaceMatches('/[^A-Z0-9]+/', '')
                                                            ->value();

                                                        $set('reference_number_normalized', $normalized);
                                                    }),

                                                Select::make('reference_type')
                                                    ->label('Type')
                                                    ->options([
                                                        'OE' => 'OE',
                                                        'OEM' => 'OEM',
                                                        'Cross Reference' => 'Cross Reference',
                                                        'Supplier Ref' => 'Supplier Ref',
                                                    ])
                                                    ->default('OE')
                                                    ->required(),
                                            ]),

                                            Hidden::make('brand_name_normalized'),
                                            Hidden::make('reference_number_normalized'),
                                        ])
                                        ->createOptionUsing(function (array $data): int {
                                            $brandRaw = trim((string) ($data['brand_name_raw'] ?? ''));
                                            $referenceRaw = trim((string) ($data['reference_number_raw'] ?? ''));
                                            $referenceType = $data['reference_type'] ?? 'OE';

                                            $brandNormalized = \Illuminate\Support\Str::of($brandRaw)
                                                ->upper()
                                                ->ascii()
                                                ->replaceMatches('/[^A-Z0-9]+/', ' ')
                                                ->trim()
                                                ->value();

                                            $referenceNormalized = \Illuminate\Support\Str::of($referenceRaw)
                                                ->upper()
                                                ->replaceMatches('/[^A-Z0-9]+/', '')
                                                ->value();

                                            $record = \App\Models\OeReference::firstOrCreate(
                                                [
                                                    'brand_name_normalized' => $brandNormalized,
                                                    'reference_number_normalized' => $referenceNormalized,
                                                ],
                                                [
                                                    'brand_name_raw' => $brandRaw,
                                                    'reference_number_raw' => $referenceRaw,
                                                    'reference_type' => $referenceType,
                                                ]
                                            );

                                            if (blank($record->reference_type) && filled($referenceType)) {
                                                $record->update([
                                                    'reference_type' => $referenceType,
                                                ]);
                                            }

                                            return $record->id;
                                        })
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                        ])
                        ->columnSpan(8),

            Grid::make(1)
                ->schema([
                    Section::make('Brand')
                        ->schema([
                            Select::make('brand_id')
                                ->label('Brand')
                                ->options(Brand::query()->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->columns(1),

                    Section::make('Category')
                        ->schema([
                            Select::make('category_id')
                                ->label('Category')
                                ->options(function () {
                                    $options = [];

                                    $parents = \App\Models\Category::query()
                                        ->with(['children' => fn ($query) => $query->orderBy('name')])
                                        ->whereNull('parent_id')
                                        ->orderBy('name')
                                        ->get();

                                    foreach ($parents as $parent) {
                                        $options[$parent->id] = $parent->name;

                                        foreach ($parent->children as $child) {
                                            $options[$child->id] = '— ' . $child->name;
                                        }
                                    }

                                    return $options;
                                })
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->helperText('Choose the most specific category, usually a subcategory.'),
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
                ])
                ->columnSpan(4),
                ]), ]);
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