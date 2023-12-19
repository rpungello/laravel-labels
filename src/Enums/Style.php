<?php

namespace Rpungello\LaravelLabels\Enums;

enum Style: int
{
    case None = 0;
    case Bold = 1;
    case Italic = 2;
    case Underline = 4;
}
