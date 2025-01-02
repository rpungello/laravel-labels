<?php

namespace Rpungello\LaravelLabels\Observers;

use Rpungello\LaravelLabels\Models\Label;

class LabelObserver
{
    public function creating(Label $label): void
    {
        foreach ($this->getDefaultValues() as $field => $defaultValue) {
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
            'label_width' => 1,
            'label_height' => 1,
        ];
    }
}
