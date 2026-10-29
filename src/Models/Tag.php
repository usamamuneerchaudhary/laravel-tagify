<?php

declare(strict_types=1);

namespace Usamamuneerchaudhary\LaravelTagify\Models;

use Illuminate\Database\Eloquent\Model;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TagUsedScopes;

class Tag extends Model
{
    use TagUsedScopes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'count' => 'integer',
        ];
    }
}
