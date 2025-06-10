<?php

namespace Sellarix\Forms\Repositories;

use Sellarix\Forms\Contracts\FormRepositoryInterface;
use Sellarix\Forms\DTO\FormDTO;
use Sellarix\Forms\Models\Form;

class FormRepository implements FormRepositoryInterface
{
    public function findBySlug(string $slug): ?FormDTO
    {
        $form = Form::query()
            ->slug($slug)
            ->withAllRelations()
            ->first();

        return $form ? FormDTO::fromModel($form) : null;
    }

    public function findById(int $id): ?FormDTO
    {
        $form = Form::query()
            ->where('id', $id)
            ->withAllRelations()
            ->first();

        return $form ? FormDTO::fromModel($form) : null;
    }

    public function getAllActive(): array
    {
        return Form::query()
            ->active() // or use FormStatus::Published->value if enum-backed
            ->withAllRelations()
            ->get()
            ->map(fn (Form $form) => FormDTO::fromModel($form))
            ->all();
    }


}
