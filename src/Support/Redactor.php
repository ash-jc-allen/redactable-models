<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support;

use AshAllenDesign\RedactableModels\Events\ModelRedacted;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Redactor
{
    public function redact(Collection $models, RedactionStrategy $strategy, Redactable $instance): void
    {
        $strategy->apply($models, $instance);

        $models->each(function (Redactable $model) {
            event(new ModelRedacted($model));
        });
    }
}
