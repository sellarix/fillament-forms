<?php

namespace Sellarix\Forms\Contracts;

use Sellarix\Forms\DTO\FormDTO;

interface FormRepositoryInterface
{
    public function findBySlug(string $slug): ?FormDTO;

    public function findById(int $id): ?FormDTO;

    public function getAllActive(): array;
}
