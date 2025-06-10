<?php

namespace Sellarix\Forms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Sellarix\Forms\Models\Form;
use Sellarix\Forms\Models\FormSubmission;

class FormSubmissionFactory extends Factory
{
    protected $model = FormSubmission::class;

    public function definition(): array
    {
        return [
            'form_id' => Form::factory(),
            'user_id' => null,
            'submitted_at' => now(),
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'metadata' => ['referrer' => $this->faker->url],
        ];
    }
}
