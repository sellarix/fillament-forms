<?php

namespace Sellarix\Forms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Sellarix\Forms\Models\FormField;
use Sellarix\Forms\Models\FormGroup;
use Sellarix\Forms\Models\FormGroupField;

class FormGroupFieldFactory extends Factory
{
    protected $model = FormGroupField::class;

    public function definition(): array
    {
        return [
            'form_group_id' => FormGroup::factory(),
            'form_field_id' => FormField::factory(),
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
