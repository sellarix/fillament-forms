<?php

namespace Sellarix\Forms\Enums;

use Sellarix\Forms\Enums\Concerns\HasLabel;

enum FormStatus: string
{
    use HasLabel;

    case Active = 'active';
    case Draft = 'draft';
    case Archived = 'archived';
}
