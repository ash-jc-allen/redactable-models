<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Data\Models;

use AshAllenDesign\RedactableModels\Interfaces\MassRedactable;
use AshAllenDesign\RedactableModels\Interfaces\RedactionStrategy;
use AshAllenDesign\RedactableModels\Support\Strategies\ReplaceContents;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MassRedactableUser extends Authenticatable implements MassRedactable
{
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function massRedactable(): Builder
    {
        // Dummy value set as placeholder.
        return static::query()->whereLike('email', '%@mass-redactable.com');
    }

    public function redactionStrategy(): RedactionStrategy
    {
        // Dummy value set as placeholder.
        return (new ReplaceContents())->replaceWith(['name' => 'MASS REDACTED']);
    }
}
