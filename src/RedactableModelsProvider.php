<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels;

use AshAllenDesign\RedactableModels\Console\Commands\RedactCommand;
use Illuminate\Support\ServiceProvider;

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
