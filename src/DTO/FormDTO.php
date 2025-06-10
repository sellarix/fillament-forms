<?php

namespace Sellarix\Forms\DTO;

use Livewire\Wireable;
use Sellarix\Forms\Models\Form;

readonly class FormDTO implements Wireable
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $description,
        public string $mode,
        public string $status,
        /** @var FormGroupDTO[] */
        public array $groups,
    ) {}

    public static function fromModel(Form $form): self
    {
        return new self(
            id: $form->id,
            name: $form->name,
            slug: $form->slug,
            description: $form->description,
            mode: $form->mode,
            status: $form->status->value,
            groups: $form->groups
                ->sortBy('sort_order')
                ->map(fn ($group) => FormGroupDTO::fromModel($group))
                ->all()
        );
    }

    public function toLivewire(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'mode' => $this->mode,
            'status' => $this->status,
            'groups' => array_map(fn ($group) => $group->toLivewire(), $this->groups),
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            id: $value['id'],
            name: $value['name'],
            slug: $value['slug'],
            description: $value['description'],
            mode: $value['mode'],
            status: $value['status'],
            groups: array_map(
                fn ($group) => FormGroupDTO::fromLivewire($group),
                $value['groups'] ?? [],
            ),
        );
    }
}
