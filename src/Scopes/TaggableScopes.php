<?php

namespace Usamamuneerchaudhary\LaravelTagify\Scopes;


trait TaggableScopes
{
    /**
     * @param $query
     * @param  array  $tags
     * @return mixed
     */
    public function scopeWithAnyTag($query, array $tags)
    {
        return $query->hasTags($tags);
    }
    
    /**
     * @param $query
     * @param  array  $tags
     * @return mixed
     */
    public function scopeWithAllTags(
        $query,
        array $tags
    ) {
        foreach ($tags as $tag) {
            $query->hasTags([$tag]);
        }
        return $query;
    }
    
    /**
     * @param $query
     * @param  array  $tags
     * @return mixed
     */
    public function scopeHasTags(
        $query,
        array $tags
    ) {
        return $query->whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('slug', $tags);
        });
    }
}
