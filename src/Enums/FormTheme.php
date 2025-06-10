<?php

namespace Sellarix\Forms\Enums;

use Sellarix\Forms\Enums\Concerns\HasLabel;

enum FormTheme: string
{
    use HasLabel;

    case Single_Page = 'single';
    case Multi_Step = 'multi';
}
