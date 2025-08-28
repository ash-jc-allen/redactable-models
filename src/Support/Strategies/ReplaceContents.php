<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\MassRedactable;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ReplaceContents implements RedactionStrategy
{
    /**
     * @var array<string,mixed>|Closure(Model): void
     */
    private array|Closure $replaceWithMappings;

    public function apply(Redactable&Model $model): void
    {
        is_array($this->replaceWithMappings)
            ? $model->forceFill($this->replaceWithMappings)
            : ($this->replaceWithMappings)($model);

        $model->save();
    }

    public function massApply(Builder $query): void
    {
        if (!is_array($this->replaceWithMappings)) {
            throw new InvalidArgumentException('Mass redaction only supports array mappings, not closures.');
        }

        $query->getQuery()->update($this->replaceWithMappings);
    }

    /**
     * @param  array<string,mixed>|Closure(Model $model): void  $replaceWith
     * @return $this
     */
    public function replaceWith(array|Closure $replaceWith): static
    {
        $this->replaceWithMappings = $replaceWith;

        return $this;
    }
}
