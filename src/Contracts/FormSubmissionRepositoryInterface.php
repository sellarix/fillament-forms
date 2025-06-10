<?php

namespace Sellarix\Forms\Contracts;

use Illuminate\Support\Collection;
use Sellarix\Forms\DTO\FormSubmissionDTO;
use Sellarix\Forms\Models\FormSubmission;

interface FormSubmissionRepositoryInterface
{
    public function getAll(): Collection;

    public function getByForm(int $formId): Collection;

    public function store(FormSubmissionDTO $submission): FormSubmission;
}
