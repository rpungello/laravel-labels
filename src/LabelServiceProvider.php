<?php

namespace Rpungello\LaravelLabels;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LabelServiceProvider extends PackageServiceProvider
{
    public function registeringPackage(): void
    {
        parent::registeringPackage();

        $this->app->singleton('laravel-label-printer', function () {
            return new LabelPrinter();
        });
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-label-printer')
            ->hasViews()
            ->hasMigration('create_labels_table')
            ->hasMigration('create_label_fields_table');
    }
}
