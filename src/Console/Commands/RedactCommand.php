<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Console\Commands;

use AshAllenDesign\RedactableModels\Interfaces\MassRedactable;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use AshAllenDesign\RedactableModels\Support\Redactor;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class RedactCommand extends Command
{
    protected $signature = 'model:redact';

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

        /** @var Redactable|MassRedactable $instance */
        $instance = new $model;
        $strategy = $instance->redactionStrategy();

        if ($this->isMassRedactable($model)) {
            $this->handleMassRedaction($model, $strategy);

            return;
        }

        $models = $instance->redactable()->get();

        $this->components->info('Redacting ['.$models->count().'] ['.$model.'] models.');

        $models->map(function (Redactable $model) use ($redactor, $strategy): void {
            $redactor->redact($model, $strategy);
        });
    }

    private function handleMassRedaction(string $model, RedactionStrategy $strategy): void
    {
        /** @var MassRedactable $instance */
        $instance = new $model;

        $query = $instance->massRedactable();
        $count = $query->count();

        $this->components->info('Mass redacting ['.$count.'] ['.$model.'] models.');

        $chunkSize = 1000;
        $query->chunk($chunkSize, function ($models) use ($strategy, $model) {
            $this->components->info('Processing chunk of ['.$models->count().'] ['.$model.'] models.');

            $strategy->massApply($models);
        });
    }

    /**
     * @return Collection<class-string>
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
                return $this->isRedactable($model) || $this->isMassRedactable($model);
            })->values();
    }

    /**
     * Determine if the given model class is redactable.
     *
     * @param  class-string  $model
     * @return bool
     */
    private function isRedactable(string $model): bool
    {
        $interfaces = class_implements($model);

        return in_array(Redactable::class, $interfaces, strict: true);
    }

    /**
     * Determine if the given model class is mass redactable.
     *
     * @param  class-string  $model
     * @return bool
     */
    private function isMassRedactable(string $model): bool
    {
        $interfaces = class_implements($model);

        return in_array(MassRedactable::class, $interfaces, strict: true);
    }
}
