<?php

it('can create a model', function () {
    $label = \Rpungello\LaravelLabels\Models\Label::factory()->create();
    \Pest\Laravel\assertModelExists($label);
});

it('can calculate effective page width', function () {
    $label = \Rpungello\LaravelLabels\Models\Label::factory()->create([
        'page_width' => 215.9,
        'horizontal_margin' => 5,
    ]);

    \PHPUnit\Framework\assertEquals(205.9, $label->getEffectivePageWidth());
});

it('can calculate effective page height', function () {
    $label = \Rpungello\LaravelLabels\Models\Label::factory()->create([
        'page_height' => 279.4,
        'vertical_margin' => 5,
    ]);

    \PHPUnit\Framework\assertEquals(269.4, $label->getEffectivePageHeight());
});

it('can calculate number of columns', function () {
    $label = \Rpungello\LaravelLabels\Models\Label::factory()->create([
        'page_width' => 215.9,
        'horizontal_margin' => 5,
        'label_width' => 100,
        'horizontal_spacing' => 5,
    ]);

    \PHPUnit\Framework\assertEquals(2, $label->getNumberOfColumns());
});

it('can calculate number of rows', function () {
    $label = \Rpungello\LaravelLabels\Models\Label::factory()->create([
        'page_height' => 279.4,
        'vertical_margin' => 5,
        'label_height' => 50,
        'vertical_spacing' => 5,
    ]);

    \PHPUnit\Framework\assertEquals(4, $label->getNumberOfRows());
});
