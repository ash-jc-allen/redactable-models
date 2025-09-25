<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class HashContents implements RedactionStrategy
{
    /**
     * @var string[]
     */
    private array $fields;

    private string $algo = 'md5';

    public function apply(Redactable&Model $model): void
    {
        foreach ($this->fields as $field) {
            $model->{$field} = hash(algo: $this->algo, data: $model->{$field});
        }

        $model->save();
    }

    public function massApply(Builder $query): void
    {
        throw new InvalidArgumentException('Mass redaction is not supported for the HashContents strategy.');
    }

    /**
     * Specify the hashing algorithm to use.
     */
    public function algo(string $algo): static
    {
        if (! in_array($algo, hash_algos(), strict: true)) {
            throw new InvalidArgumentException("The algorithm `{$algo}` is not supported.");
        }

        $this->algo = $algo;

        return $this;
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
