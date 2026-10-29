<?php

declare(strict_types=1);

namespace Usamamuneerchaudhary\LaravelTagify;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Usamamuneerchaudhary\LaravelTagify\Events\TagAttached;
use Usamamuneerchaudhary\LaravelTagify\Events\TagDetached;
use Usamamuneerchaudhary\LaravelTagify\Models\Tag;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TaggableScopes;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TagUsedScopes;

/**
 * @phpstan-require-extends Model
 */
trait Taggable
{
    use TaggableScopes, TagUsedScopes;

    /**
     * @return MorphToMany<Tag, $this>
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * @param  array<int, string>|Collection<int, mixed>|Model  $tags
     */
    public function tag(array|Collection|Model $tags): void
    {
        $this->addTags($this->getWorkableTags($tags));
    }

    /**
     * @param  array<int, string>|Collection<int, mixed>|Model|null  $tags
     */
    public function untag(array|Collection|Model|null $tags = null): void
    {
        if ($tags === null) {
            $this->removeAllTags();

            return;
        }

        $this->removeTags($this->getWorkableTags($tags));
    }

    /**
     * @param  array<int, string>|Collection<int, mixed>|Model  $tags
     */
    public function retag(array|Collection|Model $tags): void
    {
        $this->removeAllTags();
        $this->tag($tags);
    }

    private function removeAllTags(): void
    {
        $this->removeTags($this->tags()->get());
    }

    /**
     * @param  EloquentCollection<int, Tag>  $tags
     */
    private function removeTags(EloquentCollection $tags): void
    {
        if ($tags->isEmpty()) {
            return;
        }

        $detached = $this->tags()->whereIn('tags.id', $tags->modelKeys())->get();

        if ($detached->isEmpty()) {
            return;
        }

        $this->tags()->detach($detached->modelKeys());

        foreach ($detached->where('count', '>', 0) as $tag) {
            $tag->decrement('count');
        }

        Event::dispatch(new TagDetached($this, $detached));
    }

    /**
     * @param  EloquentCollection<int, Tag>  $tags
     */
    private function addTags(EloquentCollection $tags): void
    {
        if ($tags->isEmpty()) {
            return;
        }

        $sync = $this->tags()->syncWithoutDetaching($tags->modelKeys());

        /** @var array<int, int|string> $attachedIds */
        $attachedIds = Arr::get($sync, 'attached', []);

        if ($attachedIds === []) {
            return;
        }

        $attached = $tags->whereIn('id', $attachedIds)->values();

        foreach ($attached as $tag) {
            $tag->increment('count');
        }

        Event::dispatch(new TagAttached($this, $attached));
    }

    /**
     * @param  array<int, string>|Collection<int, mixed>|Model  $tags
     * @return EloquentCollection<int, Tag>
     */
    private function getWorkableTags(array|Collection|Model $tags): EloquentCollection
    {
        if (is_array($tags)) {
            return $this->getTagsModel($tags);
        }

        if ($tags instanceof Model) {
            $slug = $tags->getAttribute('slug');

            if (! is_string($slug) || $slug === '') {
                return new EloquentCollection;
            }

            return $this->getTagsModel([$slug]);
        }

        return $this->filterTagsCollection($tags);
    }

    /**
     * @param  array<int, string>  $tags
     * @return EloquentCollection<int, Tag>
     */
    private function getTagsModel(array $tags): EloquentCollection
    {
        $slugs = $this->normalizeTagNames($tags);

        if ($slugs === []) {
            return new EloquentCollection;
        }

        return Tag::query()->whereIn('slug', $slugs)->get();
    }

    /**
     * @param  Collection<int, mixed>  $tags
     * @return EloquentCollection<int, Tag>
     */
    private function filterTagsCollection(Collection $tags): EloquentCollection
    {
        $ids = [];

        foreach ($tags as $tag) {
            if (! $tag instanceof Model) {
                continue;
            }

            $key = $tag->getKey();

            if ($key !== null) {
                $ids[] = $key;
            }
        }

        if ($ids === []) {
            return new EloquentCollection;
        }

        return Tag::query()->whereIn('id', $ids)->get();
    }

    /**
     * @param  array<int, string>  $tags
     * @return array<int, string>
     */
    private function normalizeTagNames(array $tags): array
    {
        return array_values(array_filter(
            array_map(static fn (mixed $tag): string => Str::slug((string) $tag), $tags),
            static fn (string $slug): bool => $slug !== '',
        ));
    }
}
