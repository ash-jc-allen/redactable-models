<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Feature\Support\Strategies\HashContents;

use AshAllenDesign\RedactableModels\Support\Strategies\HashContents;
use AshAllenDesign\RedactableModels\Tests\Data\Models\User;
use AshAllenDesign\RedactableModels\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HashContentsTest extends TestCase
{
    #[Test]
    public function model_fields_can_be_hashed(): void
    {
        $strategy = new HashContents();
        $strategy->fields([
            'name',
        ]);

        $model = new User();

        $model->name = 'Ash Allen';
        $model->email = 'ash@example.com';
        $model->password = 'password';

        $model->save();

        $strategy->apply($model);

        $model->refresh();

        $this->assertSame('7659a2a904e2ac114d3de76d850ebd68', $model->name);
        $this->assertSame('ash@example.com', $model->email);
    }
}
