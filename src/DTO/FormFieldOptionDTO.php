<?php

namespace Sellarix\Forms\DTO;

use Livewire\Wireable;

readonly class FormFieldOptionDTO implements Wireable
{
    public function __construct(
        public string $label,
        public string $value
    ) {}

    public function toLivewire(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
        ];
    }

    public static function fromLivewire($value): self
    {
        return new self(
            label: $value['label'],
            value: $value['value'],
        );
    }
}
