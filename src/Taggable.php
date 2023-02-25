<?php

namespace Usamamuneerchaudhary\LaravelTagify;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Usamamuneerchaudhary\LaravelTagify\Models\Tag;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TaggableScopes;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TagUsedScopes;

trait Taggable
{
    use TaggableScopes, TagUsedScopes;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
    /**
     * @param $tags
     * @return void
     */
    public function tag($tags)
    {
        $this->addTags($this->getWorkableTags($tags));
    }
    
    /**
     * @param $tags
     * @return void
     */
    public function untag($tags = null)
    {
        if ($tags === null) {
            $this->removeAllTags();
            return;
        }
        $this->removeTags($this->getWorkableTags($tags));
    }
    
    /**
     * @param $tags
     * @return void
     */
    public function retag($tags)
    {
        $this->removeAllTags();
        $this->tag($tags);
    }
    
    /**
     * @return void
     */
    private function removeAllTags()
    {
        $this->removeTags($this->tags);
    }
    
    /**
     * @param  Collection  $tags
     * @return void
     */
    private function removeTags(Collection $tags)
    {
        $this->tags()->detach($tags);
        foreach ($tags->where('count', '>', 0) as $tag) {
            $tag->decrement('count');
        }
    }
    
    /**
     * @param  Collection  $tags
     * @return void
     */
    private function addTags(Collection $tags)
    {
        $sync = $this->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());
        foreach (Arr::get($sync, 'attached') as $attachedId) {
            $tags->where('id', $attachedId)->first()->increment('count');
        }
    }
    
    /**
     * @param $tags
     * check if passed in model, collection or array of strings
     * @return collection
     */
    private function getWorkableTags($tags)
    {
        if (is_array($tags)) {
            return $this->getTagsModel($tags);
        }
        
        if ($tags instanceof Model) {
            return $this->getTagsModel([$tags->slug]);
        }
        
        return $this->filterTagsCollection($tags);
    }
    
    /**
     * @param  array  $tags
     * @return mixed
     */
    private function getTagsModel(array $tags)
    {
        return Tag::whereIn('slug', $this->normalizeTagNames($tags))->get();
    }
    
    /**
     * @param  Collection  $tags
     * @return Collection
     */
    private function filterTagsCollection(Collection $tags)
    {
        return $tags->filter(function ($tag) {
            return $tag instanceof Model;
        });
    }
    
    /**
     * @param  array  $tags
     * @return array|string[]
     */
    private function normalizeTagNames(array $tags)
    {
        return array_map(function ($tag) {
            return Str::slug($tag);
        }, $tags);
    }
    
}
