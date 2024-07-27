<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Closure;
use Illuminate\Database\Eloquent\Model;

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
