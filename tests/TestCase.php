<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests;

use AshAllenDesign\RedactableModels\RedactableModelsProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    /**
     * Load package service provider.
     *
     * @param  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [RedactableModelsProvider::class];
    }
}
