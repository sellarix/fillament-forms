<?php

namespace Sellarix\Forms\DTO;

readonly class FormSubmissionDTO
{
    public function __construct(
        public int $formId,
        public ?int $userId,
        public string $ipAddress,
        public string $userAgent,
        public array $values,
        public array $metadata = [],
    ) {}
}
