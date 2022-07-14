<?php

namespace Rpungello\LaravelLabels;

use Illuminate\Contracts\Support\Arrayable;

class ArrayLabel implements PrintsOnLabels, Arrayable
{
    public function __construct(protected array $data)
    {
    }

    public function getLabelData(): array
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
