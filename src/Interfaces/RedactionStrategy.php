<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RedactionStrategy
{
    public function apply(Redactable&Model $model): void;
}
