<?php

namespace Rpungello\LaravelLabels;

use Illuminate\Support\Collection;
use Rpungello\LaravelLabels\Models\Label;
use TCPDF;

class LabelPrinter
{
    public function __construct()
    {
    }

    public function getPdfFromCollection(Label $template, Collection $models): TCPDF
    {
        return $this->getPdfFromArray($template, $models->toArray());
    }

    public function getPdfFromArray(Label $template, array $models): TCPDF
    {
        $pdf = new PdfDocument($template);
        $pdf->addLabels($models);

        return $pdf;
    }
}
