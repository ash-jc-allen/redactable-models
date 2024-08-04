<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RedactionStrategy
{
    public function apply(Collection $models, Redactable $instance): void;
}
