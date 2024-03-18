<?php

namespace Rpungello\LaravelLabels;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;
use Rpungello\LaravelLabels\Enums\BarcodeType;
use Rpungello\LaravelLabels\Models\Label;
use Rpungello\LaravelLabels\Models\LabelBarcode;
use Rpungello\LaravelLabels\Models\LabelField;
use Rpungello\LaravelStringTemplate\Facades\LaravelStringTemplate;
use Spatie\Color\Color;
use Spatie\Color\Hsl;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use TCPDF;

class PdfDocument extends TCPDF
{
    protected int $numberOfColumns;
    protected int $numberOfRows;
    protected int $currentColumn;
    protected int $currentRow;

    public function __construct(protected Label $template, protected bool $forceDebug = false)
    {
        if ($this->template->page_width > $this->template->page_height) {
            parent::__construct('L', 'mm', [$template->page_width, $template->page_height]);
        } else {
            parent::__construct('P', 'mm', [$template->page_width, $template->page_height]);
        }

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
            align: $field->alignment->value,
            x: $fieldXPos,
            y: $fieldYPos,
            valign: $field->alignment_vertical->value,
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

        if ($barcode->type === BarcodeType::OneDimensional) {
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

    /**
     * Creates a Symfony response that forces the user to download the PDF
     *
     * @param string $filename
     * @param string $disposition
     * @return BinaryFileResponse
     */
    public function downloadResponse(string $filename = 'Labels.pdf', string $disposition = 'attachment'): BinaryFileResponse
    {
        return response()->download(
            $this->saveTemp(),
            $filename,
            ['Content-Type' => 'application/pdf'],
            $disposition
        );
    }

    /**
     * Creates a Symfony response that displays the PDF in the browser without downloading it
     *
     * @param string $filename
     * @return BinaryFileResponse
     */
    public function viewResponse(string $filename = 'Labels.pdf'): BinaryFileResponse
    {
        return $this->downloadResponse($filename, 'inline');
    }

    /**
     * Saves the PDF to a temporary file and returns the path
     *
     * @param null $tempDir
     * @param string $prefix
     * @return string
     */
    public function saveTemp($tempDir = null, string $prefix = 'labels-'): string
    {
        $path = tempnam($tempDir ?: sys_get_temp_dir(), $prefix);
        $this->Output($path, 'F');

        return $path;
    }

    /**
     * Uploads the PDF to a Laravel disk
     *
     * @param string $disk
     * @param string $remotePath
     * @param array $options
     * @return bool
     */
    public function saveToDisk(string $disk, string $remotePath, array $options = []): bool
    {
        return Storage::disk($disk)
            ->writeStream(
                $remotePath,
                fopen($this->saveTemp(), 'r'),
                $options
            );
    }
}
