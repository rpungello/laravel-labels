<?php

use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\assertFileExists;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertTrue;

use Rpungello\LaravelLabels\ArrayLabel;
use Rpungello\LaravelLabels\LabelPrinter;
use Rpungello\LaravelLabels\Models\Label;

function assertEquals(int $getStatusCode, $HTTP_OK)
{

}

it('can save temporary files', function () {
    $printer = new LabelPrinter();
    $template = Label::factory()->create();
    $labels = [];
    for ($i = 0; $i < 90; $i++) {
        $labels[] = new ArrayLabel([
            'value' => 'Label #' . $i + 1,
        ]);
    }
    $pdf = $printer->getPdfFromArray($template, $labels);
    $path = $pdf->saveTemp();
    assertFileExists($path);
    assertGreaterThan(1000, filesize($path));
});

it('can save files to Laravel disks', function () {
    $this->instance(Storage::class, Storage::fake('test'));

    $printer = new LabelPrinter();
    $template = Label::factory()->create();
    $labels = [];
    for ($i = 0; $i < 90; $i++) {
        $labels[] = new ArrayLabel([
            'value' => 'Label #' . $i + 1,
        ]);
    }
    $pdf = $printer->getPdfFromArray($template, $labels);
    assertTrue($pdf->saveToDisk('test', 'test.pdf'));
    assertTrue(Storage::disk('test')->exists('test.pdf'));
});

it('can save files to Laravel disks with random names', function () {
    $this->instance(Storage::class, Storage::fake('test'));

    $printer = new LabelPrinter();
    $template = Label::factory()->create();
    $labels = [];
    for ($i = 0; $i < 90; $i++) {
        $labels[] = new ArrayLabel([
            'value' => 'Label #' . $i + 1,
        ]);
    }
    $pdf = $printer->getPdfFromArray($template, $labels);
    $remotePath = $pdf->saveToDiskFolder('test');
    assertTrue(Storage::disk('test')->exists($remotePath));
});
