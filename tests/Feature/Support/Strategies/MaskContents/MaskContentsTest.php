<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Feature\Support\Strategies\MaskContents;

use AshAllenDesign\RedactableModels\Support\Strategies\MaskContents;
use AshAllenDesign\RedactableModels\Tests\Data\Models\MassRedactableUser;
use AshAllenDesign\RedactableModels\Tests\Data\Models\User;
use AshAllenDesign\RedactableModels\Tests\TestCase;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;

class MaskContentsTest extends TestCase
{
    #[Test]
    public function model_fields_can_be_replaced(): void
    {
        $strategy = new MaskContents();
        $strategy->mask('name', '*', 0, 4);
        $strategy->mask('email', '-', 2, 3);

        $model = new User();

        $model->name = 'Ash Allen';
        $model->email = 'ash@example.com';
        $model->password = 'password';

        $model->save();

        $strategy->apply($model);

        $model->refresh();

        $this->assertSame('****Allen', $model->name);
        $this->assertSame('as---xample.com', $model->email);
    }

    #[Test]
    public function mass_redaction_throws_exception(): void
    {
        $strategy = new MaskContents();
        $strategy->mask('name', '*', 0, 4);

        $models = new \Illuminate\Database\Eloquent\Collection([new MassRedactableUser()]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Mass redaction is not supported for the MaskContents strategy.');

        $strategy->massApply($models);
    }
}
