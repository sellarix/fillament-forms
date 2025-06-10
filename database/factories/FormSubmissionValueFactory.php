<?php

namespace Sellarix\Forms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Sellarix\Forms\Models\FormField;
use Sellarix\Forms\Models\FormSubmission;
use Sellarix\Forms\Models\FormSubmissionValue;

class FormSubmissionValueFactory extends Factory
{
    protected $model = FormSubmissionValue::class;

    public function definition(): array
    {
        return [
            'form_submission_id' => FormSubmission::factory(),
            'form_field_id' => FormField::factory(),
            'value' => $this->faker->sentence,
        ];
    }
}
