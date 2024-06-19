<?php

namespace Rpungello\LaravelLabels\Observers;

use Rpungello\LaravelLabels\Models\Label;

class LabelObserver
{
    public function creating(Label $label): void
    {
        foreach($this->getDefaultValues() as $field => $defaultValue) {
            if (empty($label->getAttribute($field))) {
                $label->setAttribute($field, $defaultValue);
            }
        }
    }

    /**
     * @return array<string, int>
     */
    protected function getDefaultValues(): array
    {
        return [
            'page_width' => 1,
            'page_height' => 1,
            'horizontal_margin' => 0,
            'vertical_margin' => 0,
            'label_width' => 1,
            'label_height' => 1,
            'horizontal_spacing' => 0,
            'vertical_spacing' => 0,
            'padding' => 0,
        ];
    }
}
