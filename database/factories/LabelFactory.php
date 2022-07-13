<?php

namespace Rpungello\Label\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rpungello\Label\Models\Label;

class LabelFactory extends Factory
{
    protected $model = Label::class;

    public function definition()
    {
        return [
            'name' => fake()->word(),
            'page_width' => fake()->numberBetween(100, 300),
            'page_height' => fake()->numberBetween(100, 300),
            'label_height' => fake()->numberBetween(20, 40),
            'label_width' => fake()->numberBetween(40, 80),
            'horizontal_spacing' => fake()->randomNumber(1),
            'vertical_spacing' => fake()->randomNumber(1),
            'padding' => fake()->randomNumber(1),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
