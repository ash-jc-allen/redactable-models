<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Feature\Support\Strategies\HashContents;

use AshAllenDesign\RedactableModels\Support\Strategies\HashContents;
use AshAllenDesign\RedactableModels\Tests\Data\Models\MassRedactableUser;
use AshAllenDesign\RedactableModels\Tests\Data\Models\User;
use AshAllenDesign\RedactableModels\Tests\TestCase;
use InvalidArgumentException;
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

    #[Test]
    public function mass_redaction_throws_exception(): void
    {
        $strategy = new HashContents();
        $strategy->fields(['name']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Mass redaction is not supported for the HashContents strategy.');

        $strategy->massApply(MassRedactableUser::query());
    }

    #[Test]
    public function can_customize_hash_algorithm(): void
    {
        $strategy = new HashContents();
        $strategy->fields([
            'name',
        ]);
        $strategy->algo('sha256');

        $model = new User();

        $model->name = 'Ash Allen';
        $model->email = 'ash@example.com';
        $model->password = 'password';

        $model->save();

        $strategy->apply($model);

        $model->refresh();

        $this->assertSame('2bc8a057ffc79a7063f26d3b4bc82f8a6b77ac63a26373001f44fa5398548dbb', $model->name);
        $this->assertSame('ash@example.com', $model->email);
    }

    #[Test]
    public function set_invalid_algorithm_throw_exception(): void
    {
        $strategy = new HashContents();
        $strategy->fields(['name']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The algorithm `invalid-algo` is not supported.');

        $strategy->algo('invalid-algo');
    }
}
