<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\MassRedactable;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use PHPUnit\Framework\Assert;

class FakeStrategy implements RedactionStrategy
{
    private bool $applied = false;
    private bool $massApplied = false;

    public function apply(Redactable&Model $model): void
    {
        $this->applied = true;
    }

    public function massApply(Collection $models): Builder
    {
        $this->massApplied = true;

        $model = $models->first();
        if (!$model || !($model instanceof MassRedactable)) {
            return new Builder(app('db')->connection());
        }

        return $model->newQuery()->getQuery();
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
