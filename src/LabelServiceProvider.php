<?php

namespace Rpungello\Label;

use Rpungello\Label\Commands\LabelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasViews()
            ->hasMigration('create_laravel-labels_table')
    }
}
