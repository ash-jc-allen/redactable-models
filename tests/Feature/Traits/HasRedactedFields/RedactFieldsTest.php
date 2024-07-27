<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Feature\Traits\HasRedactedFields;

use AshAllenDesign\RedactableModels\Exceptions\RedactableFieldsException;
use AshAllenDesign\RedactableModels\Support\Strategies\ReplaceContents;
use AshAllenDesign\RedactableModels\Tests\Data\Models\User;
use AshAllenDesign\RedactableModels\Tests\TestCase;
use AshAllenDesign\RedactableModels\Traits\HasRedactedFields;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;

class RedactFieldsTest extends TestCase
{
    #[Test]
    public function model_can_be_redacted_using_default_strategy(): void
    {
        $model = new User();

        $model->name = 'Ash Allen';
        $model->email = 'ash@example.com';
        $model->password = 'password';

        $model->save();

        $model->redactFields();

        $model->refresh();

        $this->assertSame('REDACTED', $model->name);
    }

    #[Test]
    public function model_can_be_redacted_using_strategy_passed_to_method(): void
    {
        $model = new User();

        $model->name = 'Ash Allen';
        $model->email = 'ash@example.com';
        $model->password = 'password';

        $model->save();

        $model->redactFields((new ReplaceContents())->replaceWith(['name' => 'HELLO']));

        $model->refresh();

        $this->assertSame('HELLO', $model->name);
    }

    #[Test]
    public function exception_is_thrown_if_the_model_does_not_implement_the_redactable_interface(): void
    {
        $this->expectException(RedactableFieldsException::class);
        $this->expectExceptionMessage('The model must implement the [AshAllenDesign\RedactableModels\Interfaces\Redactable] interface.');

        $model = new class extends Model {
            use HasRedactedFields;
        };

        $model->redactFields();
    }
}
