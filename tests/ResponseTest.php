<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;

use Rpungello\LaravelLabels\ArrayLabel;
use Rpungello\LaravelLabels\LabelPrinter;
use Rpungello\LaravelLabels\Models\Label;
use Symfony\Component\HttpFoundation\Response;

it('can generate download responses', function () {
    $printer = new LabelPrinter();
    $template = Label::factory()->create();
    $labels = [];
    for ($i = 0; $i < 90; $i++) {
        $labels[] = new ArrayLabel([
            'value' => 'Label #' . $i + 1,
        ]);
    }
    $pdf = $printer->getPdfFromArray($template, $labels);
    $response = $pdf->downloadResponse();

    assertEquals($response->getStatusCode(), Response::HTTP_OK);
    assertEquals('application/pdf', $response->headers->get('Content-Type'));
    assertEquals('attachment; filename=Labels.pdf', $response->headers->get('Content-Disposition'));
    assertGreaterThan(1000, $response->getFile()->getSize());
});

it('can generate view responses', function () {
    $printer = new LabelPrinter();
    $template = Label::factory()->create();
    $labels = [];
    for ($i = 0; $i < 90; $i++) {
        $labels[] = new ArrayLabel([
            'value' => 'Label #' . $i + 1,
        ]);
    }
    $pdf = $printer->getPdfFromArray($template, $labels);
    $response = $pdf->viewResponse();

    assertEquals($response->getStatusCode(), Response::HTTP_OK);
    assertEquals('application/pdf', $response->headers->get('Content-Type'));
    assertEquals('inline; filename=Labels.pdf', $response->headers->get('Content-Disposition'));
    assertGreaterThan(1000, $response->getFile()->getSize());
});
