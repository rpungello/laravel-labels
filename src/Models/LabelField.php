<?php

namespace Rpungello\Label\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class LabelField extends Model
{
    use HasFactory;

    public const DEFAULT_FONT_SIZE = 12;

    public const STYLE_NONE = 0;
    public const STYLE_BOLD = 1;
    public const STYLE_ITALIC = 2;
    public const STYLE_UNDERLINE = 4;

    protected $fillable = [
        'x_pos',
        'y_pos',
        'width',
        'height',
        'font_size',
        'style',
        'alignment',
        'alignment_vertical',
        'content',
    ];

    protected $casts = [
        'x_pos' => 'float',
        'y_pos' => 'float',
        'width' => 'float',
        'height' => 'float',
        'font_size' => 'integer',
        'alignment' => 'integer',
        'alignment_vertical' => 'integer',
    ];

    public function label(): Relation
    {
        return $this->belongsTo(Label::class);
    }
}
