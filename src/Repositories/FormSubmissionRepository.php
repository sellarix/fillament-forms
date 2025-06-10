<?php

namespace Sellarix\Forms\Repositories;

use Illuminate\Support\Collection;
use Sellarix\Forms\Contracts\FormSubmissionRepositoryInterface;
use Sellarix\Forms\DTO\FormSubmissionDTO;
use Sellarix\Forms\Models\FormSubmission;
use Sellarix\Forms\Models\FormSubmissionValue;

class FormSubmissionRepository implements FormSubmissionRepositoryInterface
{
    public function getAll(): Collection
    {
        return FormSubmission::withValues()->recent()->get();
    }

    public function getByForm(int $formId): Collection
    {
        return FormSubmission::withValues()
            ->where('form_id', $formId)
            ->recent()
            ->get();
    }

    public function store(FormSubmissionDTO $submission): FormSubmission
    {
        $record = FormSubmission::create([
            'form_id' => $submission->formId,
            'user_id' => $submission->userId,
            'ip_address' => $submission->ipAddress,
            'user_agent' => $submission->userAgent,
            'submitted_at' => now(),
            'metadata' => $submission->metadata,
        ]);

        $values = collect($submission->values)
            ->map(function ($value, $fieldId) use ($record) {
                return new FormSubmissionValue([
                    'form_field_id' => $fieldId,
                    'value' => $value,
                ]);
            });

        $record->values()->saveMany($values);

        return $record->load('values.field');
    }
}
