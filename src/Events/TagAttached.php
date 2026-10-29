<?php

declare(strict_types=1);

namespace Usamamuneerchaudhary\LaravelTagify\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Usamamuneerchaudhary\LaravelTagify\Models\Tag;

class TagAttached implements ShouldDispatchAfterCommit
{
    /**
     * @param  Collection<int, Tag>  $tags
     */
    public function __construct(
        public Model $taggable,
        public Collection $tags,
    ) {}
}
