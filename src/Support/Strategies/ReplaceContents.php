<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Closure;
use Illuminate\Database\Eloquent\Model;

class ReplaceContents implements RedactionStrategy
{
    private array|Closure $replaceWithMappings;

    public function apply(Redactable&Model $model): void
    {
        is_array($this->replaceWithMappings)
            ? $model->forceFill($this->replaceWithMappings)
            : ($this->replaceWithMappings)($model);

        $model->save();
    }

    public function replaceWith(array|Closure $replaceWith): static
    {
        $this->replaceWithMappings = $replaceWith;

        return $this;
    }
}
