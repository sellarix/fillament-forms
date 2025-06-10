<?php

namespace Sellarix\Forms\DTO;

use Livewire\Wireable;
use Sellarix\Forms\Models\FormField;

readonly class FormFieldDTO implements Wireable
{
    public function __construct(
        public int $id,
        public string $name,
        public string $label,
        public string $type,
        public ?string $placeholder,
        public ?string $default,
        public bool $required,
        public int $colSpan = 0,
        /** @var FormFieldOptionDTO[] */
        public array $options = [],
    ) {}

    public static function fromModel(FormField $field, bool $required = false, int $colspan = 0): self
    {
        return new self(
            id: $field->id,
            name: $field->name,
            label: $field->label,
            type: $field->type->value,
            placeholder: $field->placeholder,
            default: $field->default ?? '',
            required: $required,
            colSpan: $colspan,
            options: collect($field->options)
                ->map(fn ($opt) => new FormFieldOptionDTO($opt['label'], $opt['value']))
                ->all()
        );
    }

    public function toLivewire(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'placeholder' => $this->placeholder,
            'default' => $this->default,
            'required' => $this->required,
            'colSpan' => $this->colSpan,
            'options' => array_map(fn ($opt) => $opt->toLivewire(), $this->options),
        ];
    }

    public static function fromLivewire($value): self
    {
        return new self(
            id: $value['id'],
            name: $value['name'],
            label: $value['label'],
            type: $value['type'],
            placeholder: $value['placeholder'],
            default: $value['default'],
            required: $value['required'],
            colSpan: $value['colSpan'] ?? 0,
            options: array_map(
                fn ($option) => FormFieldOptionDTO::fromLivewire($option),
                $value['options'] ?? [],
            ),
        );
    }
}
