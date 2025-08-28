<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Interfaces;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface RedactionStrategy
{
    public function apply(Redactable&Model $model): void;

    public function massApply(Builder $query): void;
}
