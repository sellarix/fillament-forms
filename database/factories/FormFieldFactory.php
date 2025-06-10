<?php

namespace Sellarix\Forms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Sellarix\Forms\Models\FormField;

class FormFieldFactory extends Factory
{
    protected $model = FormField::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'label' => $this->faker->sentence(2),
            'type' => $this->faker->randomElement(['text', 'email', 'textarea', 'select']),
            'placeholder' => $this->faker->optional()->word,
            'default' => $this->faker->optional()->word,
            'validation' => 'required',
            'options' => ['Option 1', 'Option 2'],
        ];
    }
}
