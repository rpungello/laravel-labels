<?php

namespace Rpungello\LaravelLabels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Rpungello\LaravelLabels\Enums\BarcodeType;

class LabelBarcode extends Model
{
    public const TYPE_1D = BarcodeType::OneDimensional;
    public const TYPE_2D = BarcodeType::TwoDimensional;

    protected $fillable = [
        'x_pos',
        'y_pos',
        'width',
        'height',
        'type',
        'symbology',
        'content',
    ];

    protected $casts = [
        'x_pos' => 'float',
        'y_pos' => 'float',
        'width' => 'float',
        'height' => 'float',
        'type' => 'int',
    ];

    public function label(): Relation
    {
        return $this->belongsTo(Label::class);
    }
}
