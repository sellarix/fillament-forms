<?php

namespace Sellarix\Forms\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Sellarix\Forms\Filament\Resources\FormResource;

class FormsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'forms';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            FormResource::class,
        ]);

        // Add additional relation managers, pages, or widgets if needed
    }

    public function boot(Panel $panel): void
    {
        // Nothing to boot at this stage
    }
}
