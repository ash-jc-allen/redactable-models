<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Support\Strategies;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReplaceContents implements RedactionStrategy
{
    /**
     * @var array<string,mixed>|Closure(Model): void
     */
    private array|Closure $replaceWithMappings;

    public function apply(Collection $models, Redactable $instance): void
    {
        is_array($this->replaceWithMappings)
            ? DB::table((new $instance())->getTable())
                ->whereIn((new $instance())->getKeyName(), $models->pluck((new $instance())->getKeyName()))
                ->update($this->replaceWithMappings)
            : ($this->replaceWithMappings)($models);
    }

    /**
     * @param  array<string,mixed>|Closure(Collection $models): void  $replaceWith
     * @return $this
     */
    public function replaceWith(array|Closure $replaceWith): static
    {
        $this->replaceWithMappings = $replaceWith;

        return $this;
    }
}
