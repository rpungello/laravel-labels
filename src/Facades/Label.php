<?php

namespace Rpungello\Label\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Rpungello\Label\Label
 */
class Label extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-labels';
    }
}
