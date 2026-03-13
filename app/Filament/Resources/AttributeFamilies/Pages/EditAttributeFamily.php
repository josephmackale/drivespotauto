<?php

namespace App\Filament\Resources\AttributeFamilies\Pages;

use App\Filament\Resources\AttributeFamilies\AttributeFamilyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAttributeFamily extends EditRecord
{
    protected static string $resource = AttributeFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
