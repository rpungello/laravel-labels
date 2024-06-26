<?php

namespace Rpungello\LaravelLabels\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Rpungello\LaravelLabels\Observers\LabelObserver;

#[ObservedBy(LabelObserver::class)]
class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'page_width',
        'page_height',
        'horizontal_margin',
        'vertical_margin',
        'label_width',
        'label_height',
        'horizontal_spacing',
        'vertical_spacing',
        'padding',
    ];

    protected $casts = [
        'page_width' => 'float',
        'page_height' => 'float',
        'horizontal_margin' => 'float',
        'vertical_margin' => 'float',
        'label_width' => 'float',
        'label_height' => 'float',
        'horizontal_spacing' => 'float',
        'vertical_spacing' => 'float',
        'padding' => 'float',
    ];

    public function fields(): Relation
    {
        return $this->hasMany(LabelField::class);
    }

    public function barcodes(): Relation
    {
        return $this->hasMany(LabelBarcode::class);
    }

    public function getEffectivePageWidth(): float
    {
        return $this->page_width - $this->horizontal_margin * 2;
    }

    public function getEffectivePageHeight(): float
    {
        return $this->page_height - $this->vertical_margin * 2;
    }

    public function getNumberOfColumns(): int
    {
        $widthRemaining = $this->getEffectivePageWidth();
        $spacingNeeded = $columns = 0;

        while (round($widthRemaining, 1) >= round($this->label_width + $spacingNeeded, 1)) {
            $columns++;
            $widthRemaining -= ($this->label_width + $spacingNeeded);
            $spacingNeeded = $this->horizontal_spacing;
        }

        return $columns;
    }

    public function getNumberOfRows(): int
    {
        $heightRemaining = $this->getEffectivePageHeight();
        $spacingNeeded = $rows = 0;

        while (round($heightRemaining, 1) >= round($this->label_height + $spacingNeeded, 1)) {
            $rows++;
            $heightRemaining -= ($this->label_height + $spacingNeeded);
            $spacingNeeded = $this->vertical_spacing;
        }

        return $rows;
    }
}
