<?php

namespace Sellarix\Forms\Filament\Resources\FormResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Sellarix\Forms\Filament\Resources\FormResource;

class EditForm extends EditRecord
{
    protected static string $resource = FormResource::class;

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Form updated successfully';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
