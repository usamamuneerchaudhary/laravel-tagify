<?php

namespace Usamamuneerchaudhary\LaravelTagify;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Usamamuneerchaudhary\LaravelTagify\Models\Tag;
use Usamamuneerchaudhary\LaravelTagify\Scopes\TaggableScopes;

trait Taggable
{
    use TaggableScopes;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
    
    public function tag($tags)
    {
        $this->addTags($this->getWorkableTags($tags));
    }
    
    public function untag($tags = null)
    {
        if ($tags === null) {
            $this->removeAllTags();
            return;
        }
        $this->removeTags($this->getWorkableTags($tags));
    }
    
    public function retag($tags)
    {
        $this->removeAllTags();
        $this->tag($tags);
    }
    
    private function removeAllTags()
    {
        $this->removeTags($this->tags);
    }
    
    private function removeTags(Collection $tags)
    {
        $this->tags()->detach($tags);
        foreach ($tags->where('count', '>', 0) as $tag) {
            $tag->decrement('count');
        }
    }
    
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
    
    private function getTagsModel(array $tags)
    {
        return Tag::whereIn('slug', $this->normalizeTagNames($tags))->get();
    }
    
    private function filterTagsCollection(Collection $tags)
    {
        return $tags->filter(function ($tag) {
            return $tag instanceof Model;
        });
    }
    
    private function normalizeTagNames(array $tags)
    {
        return array_map(function ($tag) {
            return Str::slug($tag);
        }, $tags);
    }
    
}
