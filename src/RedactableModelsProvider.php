<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels;

use Illuminate\Support\ServiceProvider;
use AshAllenDesign\RedactableModels\Console\Commands\RedactCommand;

class RedactableModelsProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->commands([
            RedactCommand::class,
        ]);
    }
}
