<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

interface RedactionStrategy
{
    public function apply(Redactable&Model $model): void;

    public function massApply(Collection $models): Builder;
}
