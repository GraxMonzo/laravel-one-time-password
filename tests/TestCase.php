<?php

namespace GraxMonzo\OneTimePassword\Tests;

use File;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var \GraxMonzo\OneTimePassword\Tests\TestModel */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // $this->initializeDirectory($this->getTempDirectory());

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * @param  $app
     */
    protected function setUpDatabase(Application $app)
    {
        file_put_contents($this->getTempDirectory() . '/database.sqlite', null);

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('otp_secret');
            $table->integer('otp_counter');
        });
    }

    protected function initializeDirectory(string $directory)
    {
        if (File::exists($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }

    public function getTempDirectory(): string
    {
        return __DIR__ . '/temp';
    }
}
