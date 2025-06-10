<?php

namespace Sellarix\Forms\Enums;

use Sellarix\Forms\Enums\Concerns\HasLabel;

enum FieldType: string
{
    use HasLabel;

    case Text = 'text';
    case Email = 'email';
    case Textarea = 'textarea';
    case Select = 'select';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case Date = 'date';
    case Number = 'number';
    case Hidden = 'hidden';

    case COMPLIANCE = 'compliance';
    case Phone = 'phone';

}
