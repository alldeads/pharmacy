<?php

namespace App\Filament\Resources\GenericResource\Pages;

use App\Filament\Resources\GenericResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageGenerics extends ManageRecords
{
    protected static string $resource = GenericResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Small)
                ->slideOver(),
        ];
    }
}
