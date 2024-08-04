<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Console\Commands;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Support\Redactor;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RedactCommand extends Command
{
    protected $signature = 'model:redact
                                {--chunk=1000 : The number of models to retrieve per chunk of models to be redacted}';

    protected $description = 'Redact the contents of the redactable fields in the database.';

    public function handle(): int
    {
        $models = $this->redactableModels();

        foreach ($models as $model) {
            $this->redactModel($model);
        }

        return static::SUCCESS;
    }

    private function redactModel(string $model): void
    {
        $redactor = app(Redactor::class);

        /** @var Redactable $instance */
        $instance = new $model;

        $strategy = $instance->redactionStrategy();

        $modelCount = $instance->redactable()->count();
        $this->components->info('Redacting ['.$modelCount.'] ['.$model.'] models.');

        $instance->redactable()->chunkById($this->option('chunk'), function ($models) use ($redactor, $strategy, $instance) {
            $redactor->redact($models, $strategy, $instance);
        });
    }

    /**
     * @return Collection<string>
     */
    private function redactableModels(): Collection
    {
        return collect((new Finder())->in(app_path('Models'))->files()->name('*.php'))
            ->map(function (SplFileInfo $model): string {
                $namespace = $this->laravel->getNamespace();

                return $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($model->getRealPath(), realpath(app_path()).DIRECTORY_SEPARATOR)
                );
            })->filter(function (string $model): bool {
                return class_exists($model);
            })->filter(function (string $model): bool {
                return $this->isRedactable($model);
            })->values();
    }

    /**
     * Determine if the given model class is redactable.
     *
     * @param  string  $model
     * @return bool
     */
    private function isRedactable(string $model): bool
    {
        $interfaces = class_implements($model);

        return in_array(Redactable::class, $interfaces, strict: true);
    }
}
