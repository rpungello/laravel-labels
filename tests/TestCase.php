<?php

namespace Rpungello\LaravelLabels\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Rpungello\LaravelLabels\LabelServiceProvider;
use Rpungello\LaravelStringTemplate\LaravelStringTemplateServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Rpungello\\LaravelLabels\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LabelServiceProvider::class,
            LaravelStringTemplateServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_labels_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_label_fields_table.php.stub';
        $migration->up();

        $migration = include __DIR__.'/../database/migrations/create_label_barcodes_table.php.stub';
        $migration->up();
    }
}
