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

    public function getPdfFromCollection(Label $template, Collection $models, bool $forceDebug = false): TCPDF
    {
        return $this->getPdfFromArray($template, $models->toArray(), $forceDebug);
    }

    public function getPdfFromArray(Label $template, array $models, bool $forceDebug = false): TCPDF
    {
        $pdf = new PdfDocument($template, $forceDebug);
        $pdf->addLabels($models);

        return $pdf;
    }
}
