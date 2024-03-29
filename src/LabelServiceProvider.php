<?php

namespace Rpungello\LaravelLabels;

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
            ->name('laravel-label-printer')
            ->hasConfigFile('labels')
            ->hasMigration('create_labels_table')
            ->hasMigration('create_label_fields_table')
            ->hasMigration('create_label_barcodes_table');

        $this->app->singleton('laravel-label-printer', function () {
            return new LabelPrinter();
        });
    }
}
