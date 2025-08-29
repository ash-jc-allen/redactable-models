<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Feature\Console\Commands;

use AshAllenDesign\RedactableModels\Console\Commands\RedactCommand;
use AshAllenDesign\RedactableModels\Tests\Data\Models\Post;
use AshAllenDesign\RedactableModels\Tests\Data\Models\User;
use AshAllenDesign\RedactableModels\Tests\TestCase;
use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class RedactCommandTest extends TestCase
{
    #[Test]
    public function models_can_be_redacted(): void
    {
        $this->createTables();
        $this->configureApplication();

        // Create 2 redactable models.
        $redactableUserOne = User::query()->forceCreate([
            'email' => 'user1@redactable.com',
            'name' => 'User One',
            'password' => 'password',
        ]);

        $redactableUserTwo = User::query()->forceCreate([
            'email' => 'user2@redactable.com',
            'name' => 'User Two',
            'password' => 'password',
        ]);

        // Create 3 mass-redactable models.
        $massRedactableUserOne = User::query()->forceCreate([
            'email' => 'user1@mass-redactable.com',
            'name' => 'User Two',
            'password' => 'password',
        ]);

        $massRedactableUserTwo = User::query()->forceCreate([
            'email' => 'user2@mass-redactable.com',
            'name' => 'User Two',
            'password' => 'password',
        ]);

        $massRedactableUserThree = User::query()->forceCreate([
            'email' => 'user3@mass-redactable.com',
            'name' => 'User Three',
            'password' => 'password',
        ]);

        // Create a user that should not be redacted.
        $nonRedactableUser = User::query()->forceCreate([
            'email' => 'do-not-redact@example.com',
            'name' => 'Do Not Redact',
            'password' => 'password',
        ]);

        // Create a Post model. We will check it's not been redacted.
        $post = Post::create(['title' => 'My first post']);

        $output = $this->runCommand();

        $this->assertStringContainsString(
            needle: 'Redacting [2] [AshAllenDesign\RedactableModels\Tests\Data\Models\User] models.',
            haystack: $output,
        );

        $this->assertStringContainsString(
            needle: 'Mass redacting [3] [AshAllenDesign\RedactableModels\Tests\Data\Models\MassRedactableUser] models.',
            haystack: $output,
        );

        $this->assertStringNotContainsStringIgnoringCase(
            needle: 'post',
            haystack: $output,
        );

        // Assert the redactable users have been changed.
        $this->assertDatabaseHas('users', [
            'id' => $redactableUserOne->id,
            'email' => 'user1@redactable.com',
            'name' => 'REDACTED',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $redactableUserTwo->id,
            'email' => 'user2@redactable.com',
            'name' => 'REDACTED',
        ]);

        // Assert the mass-redactable users have been changed.
        $this->assertDatabaseHas('users', [
            'id' => $massRedactableUserOne->id,
            'email' => 'user1@mass-redactable.com',
            'name' => 'MASS REDACTED',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $massRedactableUserTwo->id,
            'email' => 'user2@mass-redactable.com',
            'name' => 'MASS REDACTED',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $massRedactableUserThree->id,
            'email' => 'user3@mass-redactable.com',
            'name' => 'MASS REDACTED',
        ]);

        // Assert the non-redactable user has not been changed.
        $this->assertDatabaseHas('users', [
            'id' => $nonRedactableUser->id,
            'email' => 'do-not-redact@example.com',
            'name' => 'Do Not Redact',
        ]);

        // Assert the Post model has not been changed.
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'My first post',
        ]);
    }

    #[Test]
    public function message_is_displayed_if_no_redactable_model_classes_are_found(): void
    {
        $this->artisan('model:redact')
            ->assertOk()
            ->expectsOutputToContain('No redactable model classes were found.');
    }

    private function createTables(): void
    {
        Eloquent::getConnectionResolver()
            ->connection()
            ->getSchemaBuilder()
            ->create('posts', function (Blueprint $table): void {
                $table->increments('id');
                $table->string('title');
                $table->timestamps();
            });
    }

    private function configureApplication(): void
    {
        $container = new Application(__DIR__.'/../../../Data');

        Application::setInstance($container);

        Closure::bind(
            function () {
                $this->useAppPath(__DIR__.'/../../../Data');

                return $this->namespace = 'AshAllenDesign\\RedactableModels\\Tests\\Data\\';
            },
            $container,
            Application::class,
        )();
    }

    private function runCommand(): string
    {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();

        $command = new RedactCommand();
        $command->setLaravel(Application::getInstance());

        $commandResult = $command->run($input, $output);

        $this->assertSame(Command::SUCCESS, $commandResult);

        return $output->fetch();
    }
}
