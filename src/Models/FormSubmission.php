<?php

namespace Sellarix\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Sellarix\Forms\Database\Factories\FormSubmissionFactory;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'user_id',
        'submitted_at',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function newFactory(): FormSubmissionFactory
    {
        return FormSubmissionFactory::new();
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(FormSubmissionValue::class);
    }

    public function scopeWithValues(Builder $query): Builder
    {
        return $query->with('values.field');
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('submitted_at');
    }
}
