<?php

namespace Rpungello\LaravelLabels\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rpungello\LaravelLabels\LabelPrinter
 */
class LabelPrinter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-label-printer';
    }
}
