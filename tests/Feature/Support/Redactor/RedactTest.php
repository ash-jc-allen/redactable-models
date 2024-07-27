<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Feature\Support\Redactor;

use AshAllenDesign\RedactableModels\Events\ModelRedacted;
use AshAllenDesign\RedactableModels\Support\Redactor;
use AshAllenDesign\RedactableModels\Support\Strategies\FakeStrategy;
use AshAllenDesign\RedactableModels\Tests\Data\Models\User;
use AshAllenDesign\RedactableModels\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;

class RedactTest extends TestCase
{
    #[Test]
    public function strategy_can_be_applied(): void
    {
        Event::fake();

        $strategy = new FakeStrategy();

        $model = new User();

        $redactor = new Redactor();
        $redactor->redact($model, $strategy);

        $strategy->assertHasBeenApplied();

        Event::assertDispatched(
            event: ModelRedacted::class,
            callback: fn (ModelRedacted $modelRedacted) => $modelRedacted->model->is($model)
        );
    }
}
