<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Support\Str;

// TODO Can we pass this to the "ReplaceContents" class?
class HashContents implements RedactionStrategy
{
    private array $fields;

    public function apply(Redactable $model): void
    {
        foreach ($this->fields as $field) {
            $model->{$field} = hash(algo: 'md5', data: $model->{$field});
        }

        $model->save();
    }

    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }
}