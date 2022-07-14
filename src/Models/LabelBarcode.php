<?php

namespace Rpungello\LaravelLabels\Models;

use Illuminate\Database\Eloquent\Relations\Relation;

class LabelBarcode extends \Illuminate\Database\Eloquent\Model
{
    public const TYPE_1D = 0;
    public const TYPE_2D = 1;

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
