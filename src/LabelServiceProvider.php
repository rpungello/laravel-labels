<?php

namespace Rpungello\Label;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rpungello\Label\Commands\LabelCommand;

class LabelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-labels')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-labels_table')
            ->hasCommand(LabelCommand::class);
    }
}
