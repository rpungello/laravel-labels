<?php

use Rpungello\LaravelLabels\Enums\BarcodeType;
use Rpungello\LaravelLabels\Models\Label;
use Rpungello\LaravelLabels\Models\LabelBarcode;
use Rpungello\LaravelLabels\Models\LabelField;

it('loads correct model classes', function () {
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
    $template->barcodes()->create([
        'x_pos' => 0,
        'y_pos' => 0,
        'width' => 66.7,
        'height' => 25.4,
        'type' => BarcodeType::OneDimensional,
        'symbology' => 'C128',
        'content' => '{value}',
    ]);

    expect($template->fields)->toHaveCount(1)
        ->and($template->fields[0])->toBeInstanceOf(LabelField::class)
        ->and($template->fields[0]->label)->toBeInstanceOf(Label::class)
        ->and($template->barcodes)->toHaveCount(1)
        ->and($template->barcodes[0])->toBeInstanceOf(LabelBarcode::class)
        ->and($template->barcodes[0]->label)->toBeInstanceOf(Label::class);

});
