<?php

namespace Sellarix\Forms\Filament\Resources\FormResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Sellarix\Forms\Filament\Resources\FormResource;

class CreateForm extends CreateRecord
{
    protected static string $resource = FormResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Form created successfully';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
}
