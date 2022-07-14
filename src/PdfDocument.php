<?php

namespace Rpungello\LaravelLabels;

use Illuminate\Contracts\Support\Arrayable;
use Rpungello\LaravelLabels\Models\Label;
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

    public function __construct(protected Label $template)
    {
        parent::__construct('P', 'mm', [$template->page_width, $template->page_height]);

        $this->numberOfColumns = $this->template->getNumberOfColumns();
        $this->numberOfRows = $this->template->getNumberOfRows();
        $this->currentColumn = $this->currentRow = 0;
        $this->AddPage();
    }

    public function addLabels(Arrayable|array $labels): void
    {
        foreach ($labels as $label) {
            $this->addLabel($label);
        }
    }

    public function addLabel(PrintsOnLabels $data): void
    {
        list($xPos, $yPos) = $this->getCurrentCoordinates();
        $this->addDebugRectangles($xPos, $yPos);

        foreach ($this->template->fields as $field) {
            $this->addLabelField($field, $data, $xPos, $yPos);
        }
        $this->currentColumn++;
        $this->checkPage();
    }

    private function getCurrentCoordinates(): array
    {
        return [
            $this->template->horizontal_margin + $this->currentColumn * ($this->template->label_width + $this->template->horizontal_spacing) + $this->template->padding,
            $this->template->vertical_margin + $this->currentRow * ($this->template->label_height + $this->template->vertical_spacing) + $this->template->padding,
        ];
    }

    private function debuggingRectangle(float $xPos, float $yPos, float $width, float $height, Color $color)
    {
        if (config('app.debug')) {
            $this->setDrawColor($color->red(), $color->green(), $color->blue());
            $this->Rect($xPos, $yPos, $width, $height);
        }
    }

    private function addLabelField(LabelField $field, PrintsOnLabels $data, float $xPos, float $yPos)
    {
        $fieldXPos = $xPos + $field->x_pos;
        $fieldYPos = $yPos + $field->y_pos;

        $maxWidth = $this->template->label_width - $this->template->padding * 2 - $field->x_pos;
        $maxHeight = $this->template->label_height - $this->template->padding * 2 - $field->y_pos;

        $this->MultiCell(
            is_null($field->width) ? $maxWidth : min($field->width, $maxWidth),
            is_null($field->height) ? $maxHeight : min($field->height, $maxHeight),
            LaravelStringTemplate::format($field->content, $data->getLabelData()),
            align: $field->alignment,
            x: $fieldXPos,
            y: $fieldYPos,
            valign: $field->alignment_vertical,
            fitcell: true,
        );
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

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M'): void
    {
        parent::Cell($w, $h, $txt, $this->shouldDisplayBorder($border), $ln, $align, $fill, $link, $stretch, $ignore_min_height, $calign, $valign);
    }

    private function shouldDisplayBorder(mixed $border): int
    {
        return $border || config('app.debug');
    }

    public function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false, $ln = 1, $x = null, $y = null, $reseth = true, $stretch = 0, $ishtml = false, $autopadding = true, $maxh = 0, $valign = 'T', $fitcell = false): int|string
    {
        return parent::MultiCell($w, $h, $txt, $this->shouldDisplayBorder($border), $align, $fill, $ln, $x, $y, $reseth, $stretch, $ishtml, $autopadding, $maxh, $valign, $fitcell); // TODO: Change the autogenerated stub
    }

    private function addDebugRectangles(float $xPos, float $yPos)
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
}