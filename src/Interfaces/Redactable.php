<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Interfaces;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface Redactable
{
    public function redactable(): Builder;

    public function redactionStrategy(): RedactionStrategy;
}
