<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support;

use AshAllenDesign\RedactableModels\Events\ModelRedacted;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;

class Redactor
{
    public function redact(Redactable $model, RedactionStrategy $strategy): void
    {
        $strategy->apply($model);

        event(new ModelRedacted($model));
    }
}
