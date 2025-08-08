<?php

namespace App\Filament\Resources\PriorityResource\Pages;

use App\Filament\Resources\PriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePriority extends CreateRecord
{
    protected static string $resource = PriorityResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
