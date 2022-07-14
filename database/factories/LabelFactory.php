<?php

namespace Rpungello\LaravelLabels\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Rpungello\LaravelLabels\Models\Label;

class LabelFactory extends Factory
{
    protected $model = Label::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'page_width' => $this->faker->numberBetween(100, 300),
            'page_height' => $this->faker->numberBetween(100, 300),
            'label_height' => $this->faker->numberBetween(20, 40),
            'label_width' => $this->faker->numberBetween(40, 80),
            'horizontal_spacing' => $this->faker->randomNumber(1),
            'vertical_spacing' => $this->faker->randomNumber(1),
            'padding' => $this->faker->randomNumber(1),
        ];
    }
}
