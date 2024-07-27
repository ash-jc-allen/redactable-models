<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Illuminate\Support\Str;

// TODO Can we pass this to the "ReplaceContents" class?
class MaskContents implements RedactionStrategy
{
    private array $masks;

    public function apply(Redactable $model): void
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

    public function mask($field, $character, $index, $length = null, $encoding = 'UTF-8'): static
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
