<?php

namespace Sellarix\Forms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sellarix\Forms\Forms
 */
class Forms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Sellarix\Forms\Forms::class;
    }
}
