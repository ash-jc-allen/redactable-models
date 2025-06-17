<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Interfaces;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface MassRedactable
{
    public function massRedactable(): Builder;

    public function redactionStrategy(): RedactionStrategy;
}
