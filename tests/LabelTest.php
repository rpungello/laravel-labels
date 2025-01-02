<?php

use function Pest\Laravel\assertModelExists;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

use Rpungello\LaravelLabels\ArrayLabel;
use Rpungello\LaravelLabels\LabelPrinter;
use Rpungello\LaravelLabels\Models\Label;
use Rpungello\LaravelLabels\Observers\LabelObserver;

it('can create a model', function () {
    $label = Label::factory()->create();
    assertModelExists($label);
});

it('can calculate effective page width', function () {
    $label = Label::factory()->create([
        'page_width' => 215.9,
        'horizontal_margin' => 5,
    ]);

    assertEquals(205.9, $label->getEffectivePageWidth());
});

it('can calculate effective page height', function () {
    $label = Label::factory()->create([
        'page_height' => 279.4,
        'vertical_margin' => 5,
    ]);

    assertEquals(269.4, $label->getEffectivePageHeight());
});

it('can calculate number of columns', function () {
    $label = Label::factory()->create([
        'page_width' => 215.9,
        'horizontal_margin' => 5,
        'label_width' => 100,
        'horizontal_spacing' => 5,
    ]);

    assertEquals(2, $label->getNumberOfColumns());
});

it('can calculate number of rows', function () {
    $label = Label::factory()->create([
        'page_height' => 279.4,
        'vertical_margin' => 5,
        'label_height' => 50,
        'vertical_spacing' => 5,
    ]);

    assertEquals(4, $label->getNumberOfRows());
});

it('can avoid rounding errors', function () {
    $label = Label::factory()->create([
        'page_height' => 279.4,
        'vertical_margin' => 12.7,
        'label_height' => 25.4,
        'vertical_spacing' => 0,
    ]);

    assertEquals(10, $label->getNumberOfRows());
});

it('prints the correct number of pages', function () {
    $printer = new LabelPrinter();
    $template = Label::factory()->create([
        'page_width' => 215.9,
        'page_height' => 279.4,
        'horizontal_margin' => 4.8,
        'vertical_margin' => 12.7,
        'label_width' => 66.7,
        'label_height' => 25.4,
        'horizontal_spacing' => 2.9,
        'vertical_spacing' => 0,
    ]);
    $template->fields()->create([
        'x_pos' => 0,
        'y_pos' => 0,
        'width' => 66.7,
        'height' => 25.4,
        'content' => '{value}',
    ]);

    $labels = [];
    for ($i = 0; $i < 90; $i++) {
        $labels[] = new ArrayLabel([
            'value' => 'Label #' . $i + 1,
        ]);
    }

    $pdf = $printer->getPdfFromArray($template, $labels);
    assertEquals($pdf->getNumPages(), 3);
});

it('can create labels with default values', function () {
    $label = new Label([
        'name' => 'Test Label',
    ]);
    $observer = new LabelObserver();
    $observer->creating($label);
    assertTrue($label->save());
});

it('can create labels with default values without overriding specified ones', function () {
    $label = new Label([
        'name' => 'Test Label',
        'label_width' => 100,
    ]);
    $observer = new LabelObserver();
    $observer->creating($label);
    assertTrue($label->save());
    assertEquals($label->label_width, 100);
});
