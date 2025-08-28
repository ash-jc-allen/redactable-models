<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Events;

use AshAllenDesign\RedactableModels\Interfaces\Redactable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelRedacted
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Redactable $model)
    {
        //
    }
}
