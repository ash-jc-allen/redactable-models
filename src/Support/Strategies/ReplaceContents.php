<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;

class ReplaceContents implements RedactionStrategy
{
    private array $replaceWith;

    public function apply(Redactable $model): void
    {
        $model->forceFill($this->replaceWith)->save();
    }

    public function replaceWith(array $replaceWith): static
    {
        $this->replaceWith = $replaceWith;

        return $this;
    }
}
