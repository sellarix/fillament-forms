<?php

namespace Sellarix\Forms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Sellarix\Forms\Models\Form;
use Sellarix\Forms\Models\FormGroup;

class FormGroupFactory extends Factory
{
    protected $model = FormGroup::class;

    public function definition(): array
    {
        return [
            'form_id' => Form::factory(),
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->slug,
            'description' => $this->faker->optional()->sentence,
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
