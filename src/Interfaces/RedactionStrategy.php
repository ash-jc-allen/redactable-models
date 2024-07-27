<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Interfaces;

interface RedactionStrategy
{
    public function apply(Redactable $model): void;
}
