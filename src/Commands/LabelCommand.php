<?php

namespace Rpungello\Label\Commands;

use Illuminate\Console\Command;

class LabelCommand extends Command
{
    public $signature = 'laravel-labels';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
