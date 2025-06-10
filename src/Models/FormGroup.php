<?php

namespace Sellarix\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Sellarix\Forms\Database\Factories\FormGroupFactory;

class FormGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'title',
        'subtitle',
        'slug',
        'description',
        'sort_order',
        'depends_on_field',
        'depends_on_value',
    ];

    protected static function newFactory(): FormGroupFactory
    {
        return FormGroupFactory::new();
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormGroupField::class)->with('field')->orderBy('sort_order');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
