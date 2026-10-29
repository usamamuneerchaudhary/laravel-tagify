<?php

declare(strict_types=1);

namespace Usamamuneerchaudhary\LaravelTagify\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @phpstan-require-extends Model
 */
trait TaggableScopes
{
    /**
     * @param  Builder<static>  $query
     * @param  array<int, string>  $tags
     * @return Builder<static>
     */
    public function scopeWithAnyTag(Builder $query, array $tags): Builder
    {
        return $query->hasTags($tags);
    }

    /**
     * @param  Builder<static>  $query
     * @param  array<int, string>  $tags
     * @return Builder<static>
     */
    public function scopeWithAllTags(Builder $query, array $tags): Builder
    {
        foreach ($tags as $tag) {
            $query->hasTags([$tag]);
        }

        return $query;
    }

    /**
     * @param  Builder<static>  $query
     * @param  array<int, string>  $tags
     * @return Builder<static>
     */
    public function scopeHasTags(Builder $query, array $tags): Builder
    {
        $slugs = $this->normalizeScopeTagNames($tags);

        return $query->whereHas('tags', function (Builder $query) use ($slugs): void {
            $query->whereIn('slug', $slugs);
        });
    }

    /**
     * @param  array<int, string>  $tags
     * @return array<int, string>
     */
    private function normalizeScopeTagNames(array $tags): array
    {
        return array_values(array_filter(
            array_map(static fn (mixed $tag): string => Str::slug((string) $tag), $tags),
            static fn (string $slug): bool => $slug !== '',
        ));
    }
}
