<?php

declare(strict_types=1);

namespace AshAllenDesign\RedactableModels\Tests\Data\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Post extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];
}
