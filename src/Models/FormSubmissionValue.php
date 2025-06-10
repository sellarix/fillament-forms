<?php

namespace Sellarix\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sellarix\Forms\Database\Factories\FormSubmissionValueFactory;

class FormSubmissionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_submission_id',
        'form_field_id',
        'value',
    ];

    protected static function newFactory(): FormSubmissionValueFactory
    {
        return FormSubmissionValueFactory::new();
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'form_field_id', 'id');
    }
}
