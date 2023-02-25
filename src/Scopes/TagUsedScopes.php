<?php

namespace Usamamuneerchaudhary\LaravelTagify\Scopes;


trait TagUsedScopes
{
    
    /**
     * @param $query
     * @param $count
     * @return mixed
     */
    public function scopeUsedGte($query, $count)
    {
        return $query->where('count', '>=', $count);
    }
    
    /**
     * @param $query
     * @param $count
     * @return mixed
     */
    public function scopeUsedGt($query, $count)
    {
        return $query->where('count', '>', $count);
    }
    
    /**
     * @param $query
     * @param $count
     * @return mixed
     */
    public function scopeUsedLte($query, $count)
    {
        return $query->where('count', '<=', $count);
    }
    
    /**
     * @param $query
     * @param $count
     * @return mixed
     */
    public function scopeUsedLt($query, $count)
    {
        return $query->where('count', '<', $count);
    }
    
}
