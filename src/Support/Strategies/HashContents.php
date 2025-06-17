<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\MassRedactable;
use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use InvalidArgumentException;

class HashContents implements RedactionStrategy
{
    /**
     * @var string[]
     */
    private array $fields;

    public function apply(Redactable&Model $model): void
    {
        foreach ($this->fields as $field) {
            $model->{$field} = hash(algo: 'md5', data: $model->{$field});
        }

        $model->save();
    }

    public function massApply(Collection $models): Builder
    {
        throw new InvalidArgumentException('Mass redaction is not supported for the HashContents strategy.');
    }

    /**
     * @param  string[]  $fields
     * @return $this
     */
    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }
}
