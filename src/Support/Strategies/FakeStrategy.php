<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\MassRedactable;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert;
use RuntimeException;

class FakeStrategy implements RedactionStrategy
{
    private bool $applied = false;
    private bool $massApplied = false;

    public function apply(Redactable&Model $model): void
    {
        $this->applied = true;
    }

    public function massApply(Builder $query): void
    {
        throw new RuntimeException('Implement this if we need it for testing.');
    }

    public function assertHasBeenApplied(): void
    {
        Assert::assertTrue(
            $this->applied,
            'The redaction strategy has not been applied.',
        );
    }

    public function assertHasBeenMassApplied(): void
    {
        Assert::assertTrue(
            $this->massApplied,
            'The mass redaction strategy has not been applied.',
        );
    }
}
