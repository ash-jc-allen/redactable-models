<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MaskContents implements RedactionStrategy
{
    private array $masks;

    public function apply(Redactable&Model $model): void
    {
        foreach ($this->masks as $mask) {
            $value = $model->{$mask['field']};

            $maskedValue = Str::mask(
                string: $value,
                character: $mask['character'],
                index: $mask['index'],
                length: $mask['length'],
                encoding: $mask['encoding']
            );

            $model->{$mask['field']} = $maskedValue;
        }

        $model->save();
    }

    public function massApply(Builder $query): void
    {
        throw new InvalidArgumentException('Mass redaction is not supported for the MaskContents strategy.');
    }

    public function mask(string $field, string $character, int $index, ?int $length = null, string $encoding = 'UTF-8'): static
    {
        $this->masks[] = [
            'field' => $field,
            'character' => $character,
            'index' => $index,
            'length' => $length,
            'encoding' => $encoding,
        ];

        return $this;
    }
}
