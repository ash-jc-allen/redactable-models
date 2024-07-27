<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert;

class FakeStrategy implements RedactionStrategy
{
    private bool $applied = false;

    public function apply(Redactable&Model $model): void
    {
        $this->applied = true;
    }

    public function assertHasBeenApplied(): void
    {
        Assert::assertTrue(
            $this->applied,
            'The redaction strategy has not been applied.',
        );
    }
}
