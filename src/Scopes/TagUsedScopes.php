<?php

declare(strict_types=1);

namespace Usamamuneerchaudhary\LaravelTagify\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpstan-require-extends Model
 */
trait TagUsedScopes
{
    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeUsedGte(Builder $query, int $count): Builder
    {
        return $query->where('count', '>=', $count);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeUsedGt(Builder $query, int $count): Builder
    {
        return $query->where('count', '>', $count);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeUsedLte(Builder $query, int $count): Builder
    {
        return $query->where('count', '<=', $count);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeUsedLt(Builder $query, int $count): Builder
    {
        return $query->where('count', '<', $count);
    }
}
