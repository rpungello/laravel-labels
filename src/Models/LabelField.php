<?php

namespace Rpungello\LaravelLabels\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Rpungello\LaravelLabels\Enums\HorizontalAlignment;
use Rpungello\LaravelLabels\Enums\Style;
use Rpungello\LaravelLabels\Enums\VerticalAlignment;

class LabelField extends Model
{
    public const DEFAULT_FONT_SIZE = 12;

    /**
     * @deprecated use Style::None
     */
    public const STYLE_NONE = Style::None;

    /**
     * @deprecated use Style::Bold
     */
    public const STYLE_BOLD = Style::Bold;

    /**
     * @deprecated use Style::Italic
     */
    public const STYLE_ITALIC = Style::Italic;

    /**
     * @deprecated use Style::Underline
     */
    public const STYLE_UNDERLINE = Style::Underline;

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
        'style' => 'integer',
        'alignment' => HorizontalAlignment::class,
        'alignment_vertical' => VerticalAlignment::class,
    ];

    public function label(): Relation
    {
        return $this->belongsTo(Label::class);
    }
}
