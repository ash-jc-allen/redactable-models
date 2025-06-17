<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\MassRedactable;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

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

    public function massApply(Collection $models): Builder
    {
        if (!is_array($this->replaceWithMappings)) {
            throw new \InvalidArgumentException('Mass redaction only supports array mappings, not closures.');
        }

        $model = $models->first();
        if (!($model instanceof MassRedactable)) {
            return new Builder(app('db')->connection());
        }

        $query = $model->newQuery()
            ->whereIn('id', $models->pluck('id'))
            ->getQuery();

        $query->update($this->replaceWithMappings);

        return $query;
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
