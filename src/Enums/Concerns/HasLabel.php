<?php

namespace Sellarix\Forms\Enums\Concerns;

trait HasLabel
{
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public function label(): string
    {
        return str_replace('_', ' ', ucwords($this->name));
    }
}
