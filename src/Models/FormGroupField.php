<?php

namespace Sellarix\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sellarix\Forms\Database\Factories\FormGroupFieldFactory;

class FormGroupField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_group_id',
        'form_field_id',
        'required',
        'colspan',
        'sort_order',
    ];

    protected static function newFactory(): FormGroupFieldFactory
    {
        return FormGroupFieldFactory::new();
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(FormGroup::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'form_field_id', 'id'); // ✅ Explicit FK
    }
}
