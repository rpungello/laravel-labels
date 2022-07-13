<?php

namespace Rpungello\Label\Facades;

use Illuminate\Support\Facades\Facade;

class LabelPrinter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-label-printer';
    }
}
