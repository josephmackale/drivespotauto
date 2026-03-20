<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected Width|string|null $maxContentWidth = 'full';

    protected function afterCreate(): void
    {
        $this->record->engines()->sync($this->data['selected_engine_ids'] ?? []);
    }
}