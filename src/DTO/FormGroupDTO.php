<?php

namespace Sellarix\Forms\DTO;

use Livewire\Wireable;
use Sellarix\Forms\Models\FormGroup;

readonly class FormGroupDTO implements Wireable
{
    public function __construct(
        public string $title,
        public ?string $subtitle,
        public string $slug,
        public ?string $description,
        /** @var FormFieldDTO[] */
        public array $fields,
    ) {}

    public static function fromModel(FormGroup $group): self
    {
        return new self(
            title: $group->title,
            subtitle: $group->subtitle,
            slug: $group->slug,
            description: $group->description,
            fields: $group->fields
                ->map(fn ($groupField) => FormFieldDTO::fromModel(
                    $groupField->field,
                    $groupField->required,
                    $groupField->colspan,
                ))
                ->all()
        );
    }

    public function toLivewire(): array
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'slug' => $this->slug,
            'description' => $this->description,
            'fields' => array_map(fn ($field) => $field->toLivewire(), $this->fields),
        ];
    }

    public static function fromLivewire($value): self
    {
        return new self(
            title: $value['title'],
            subtitle: $value['subtitle'],
            slug: $value['slug'],
            description: $value['description'],
            fields: array_map(
                fn ($field) => FormFieldDTO::fromLivewire($field),
                $value['fields'] ?? [],
            ),
        );
    }
}
