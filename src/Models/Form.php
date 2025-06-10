<?php

namespace Sellarix\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Sellarix\Forms\Database\Factories\FormFactory;
use Sellarix\Forms\Enums\EmailTemplateMode;
use Sellarix\Forms\Enums\FormStatus;
use Sellarix\Forms\Enums\FormTheme;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'mode',
        'email_to_user',
        'email_template',
    ];

    protected $casts = [
        'theme' => FormTheme::class,
        'status' => FormStatus::class,
        'email_to_user' => 'boolean',
    ];

    protected static function newFactory(): FormFactory
    {
        return FormFactory::new();
    }

    public function groups(): HasMany
    {
        return $this->hasMany(FormGroup::class)->orderBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function scopeWithAllRelations(Builder $query): Builder
    {
        return $query->with(['groups.fields.field', 'submissions']);
    }

    public function scopeSlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', FormStatus::Active->value);
    }
}
