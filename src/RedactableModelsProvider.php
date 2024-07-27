<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels;

use Illuminate\Support\ServiceProvider;

class RedactableModelsProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->commands([
            Console\Commands\RedactCommand::class,
        ]);
    }
}
