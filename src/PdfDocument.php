<?php

namespace Rpungello\LaravelLabels;

use Illuminate\Contracts\Support\Arrayable;
use Rpungello\LaravelLabels\Models\Label;
use Rpungello\LaravelLabels\Models\LabelBarcode;
use Rpungello\LaravelLabels\Models\LabelField;
use Rpungello\LaravelStringTemplate\Facades\LaravelStringTemplate;
use Spatie\Color\Color;
use Spatie\Color\Hsl;
use TCPDF;

class PdfDocument extends TCPDF
{
    protected int $numberOfColumns;
    protected int $numberOfRows;
    protected int $currentColumn;
    protected int $currentRow;

    public function __construct(protected Label $template, protected bool $forceDebug = false)
    {
        parent::__construct('P', 'mm', [$template->page_width, $template->page_height]);

        $this->numberOfColumns = $this->template->getNumberOfColumns();
        $this->numberOfRows = $this->template->getNumberOfRows();
        $this->currentColumn = $this->currentRow = 0;
        $this->AddPage();
        $this->setAutoPageBreak(false);
    }

    public function addLabels(Arrayable|array $labels): void
    {
        foreach ($labels as $label) {
            $this->addLabel($label);
        }
    }

    public function addLabel(PrintsOnLabels $data): void
    {
        $this->checkPage();

        list($xPos, $yPos) = $this->getCurrentCoordinates();
        $this->addDebugRectangles($xPos, $yPos);

        foreach ($this->template->fields as $field) {
            $this->addLabelField($field, $data, $xPos, $yPos);
        }

        foreach ($this->template->barcodes as $barcode) {
            $this->addLabelBarcode($barcode, $data, $xPos, $yPos);
        }

        $this->currentColumn++;
    }

    private function getCurrentCoordinates(): array
    {
        return [
            $this->template->horizontal_margin + $this->currentColumn * ($this->template->label_width + $this->template->horizontal_spacing) + $this->template->padding,
            $this->template->vertical_margin + $this->currentRow * ($this->template->label_height + $this->template->vertical_spacing) + $this->template->padding,
        ];
    }

    private function addDebugRectangles(float $xPos, float $yPos): void
    {
        $this->debuggingRectangle(
            $xPos - $this->template->padding,
            $yPos - $this->template->padding,
            $this->template->label_width,
            $this->template->label_height,
            Hsl::fromString('hsl(0, 40%, 70%)')
        );

        $this->debuggingRectangle(
            $xPos,
            $yPos,
            $this->template->label_width - $this->template->padding * 2,
            $this->template->label_height - $this->template->padding * 2,
            Hsl::fromString('hsl(120, 40%, 70%)')
        );

        $this->setDrawColor(0, 0, 0);
    }

    private function debuggingRectangle(float $xPos, float $yPos, float $width, float $height, Color $color): void
    {
        if ($this->forceDebug || config('app.debug')) {
            $this->setDrawColor($color->red(), $color->green(), $color->blue());
            $this->Rect($xPos, $yPos, $width, $height);
        }
    }

    private function addLabelField(LabelField $field, PrintsOnLabels $data, float $xPos, float $yPos): void
    {
        $fieldXPos = $xPos + $field->x_pos;
        $fieldYPos = $yPos + $field->y_pos;

        $maxWidth = $this->template->label_width - $this->template->padding * 2 - $field->x_pos;
        $maxHeight = $this->template->label_height - $this->template->padding * 2 - $field->y_pos;

        if ($this->forceDebug || config('app.debug')) {
            $this->setDrawColor(220, 220, 220);
        }

        $this->setFontSize($field->font_size);

        $this->MultiCell(
            is_null($field->width) ? $maxWidth : min($field->width, $maxWidth),
            is_null($field->height) ? $maxHeight : min($field->height, $maxHeight),
            LaravelStringTemplate::format($field->content, $data->getLabelData()),
            border: $this->forceDebug || config('app.debug'),
            align: $field->alignment,
            x: $fieldXPos,
            y: $fieldYPos,
            valign: $field->alignment_vertical,
            fitcell: true,
        );
    }

    private function addLabelBarcode(LabelBarcode $barcode, PrintsOnLabels $data, float $xPos, float $yPos): void
    {
        $barcodeXPos = $xPos + $barcode->x_pos;
        $barcodeYPos = $yPos + $barcode->y_pos;

        $maxWidth = $this->template->label_width - $this->template->padding * 2 - $barcode->x_pos;
        $maxHeight = $this->template->label_height - $this->template->padding * 2 - $barcode->y_pos;

        $formattedContent = LaravelStringTemplate::format($barcode->content, $data->getLabelData());

        if ($barcode->type === LabelBarcode::TYPE_1D) {
            $this->write1DBarcode(
                $formattedContent,
                $barcode->symbology,
                $barcodeXPos,
                $barcodeYPos,
                is_null($barcode->width) ? $maxWidth : min($barcode->width, $maxWidth),
                is_null($barcode->height) ? $maxHeight : min($barcode->height, $maxHeight),
            );
        } else {
            $this->write2DBarcode(
                $formattedContent,
                $barcode->symbology,
                $barcodeXPos,
                $barcodeYPos,
                is_null($barcode->width) ? $maxWidth : min($barcode->width, $maxWidth),
                is_null($barcode->height) ? $maxHeight : min($barcode->height, $maxHeight),
            );
        }
    }

    private function checkPage(): void
    {
        if ($this->currentColumn === $this->numberOfColumns) {
            $this->currentColumn = 0;
            $this->currentRow++;
        }

        if ($this->currentRow === $this->numberOfRows) {
            $this->currentColumn = $this->currentRow = 0;
            $this->AddPage();
        }
    }

    public function Header(): void
    {
    }

    public function Footer(): void
    {
    }
}
