<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Feature\Support\Strategies\ReplaceContents;

use AshAllenDesign\RedactableModels\Support\Strategies\ReplaceContents;
use AshAllenDesign\RedactableModels\Tests\Data\Models\User;
use AshAllenDesign\RedactableModels\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ReplaceContentsTest extends TestCase
{
    #[Test]
    public function models_can_be_redacted(): void
    {
        $strategy = new ReplaceContents();
        $strategy->replaceWith(['name' => 'John Doe']);

        $model = new User();

        $model->name = 'Ash Allen';
        $model->email = 'ash@example.com';
        $model->password = 'password';

        $model->save();

        $strategy->apply($model);

        $model->refresh();

        $this->assertSame('John Doe', $model->name);
        $this->assertSame('ash@example.com', $model->email);
    }

    #[Test]
    public function exception_is_thrown_if_the_replacements_have_not_been_set_yet(): void
    {

    }
}
