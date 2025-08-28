<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Traits;

use AshAllenDesign\RedactableModels\Exceptions\RedactableFieldsException;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use AshAllenDesign\RedactableModels\Support\Redactor;

trait HasRedactableFields
{
    /**
     * @throws RedactableFieldsException
     */
    public function redactFields(?RedactionStrategy $strategy = null): void
    {
        if (! in_array(Redactable::class, class_implements($this), true)) {
            throw new RedactableFieldsException('The model must implement the ['.Redactable::class.'] interface.');
        }

        app(Redactor::class)->redact(
            model: $this,
            strategy: $strategy ?? $this->redactionStrategy(),
        );
    }
}
