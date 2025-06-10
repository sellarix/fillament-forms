<?php

namespace Sellarix\Forms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sellarix\Forms\Database\Factories\FormFieldFactory;
use Sellarix\Forms\Enums\FieldType;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'type',
        'placeholder',
        'default',
        'options',
    ];

    protected $casts = [
        'type' => FieldType::class,
        'options' => 'array',
    ];

    protected static function newFactory(): FormFieldFactory
    {
        return FormFieldFactory::new();
    }

    public function groupFields(): HasMany
    {
        return $this->hasMany(FormGroupField::class);
    }

    public function submissionValues(): HasMany
    {
        return $this->hasMany(FormSubmissionValue::class);
    }

    public function scopeEmailField(Builder $query): Builder
    {
        return $query->where('type', 'email');
    }
}
