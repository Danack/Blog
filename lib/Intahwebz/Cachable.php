<?php

namespace Intahwebz;

trait Cachable
{
    public function getCacheKeyName($id)
    {
        return get_class($this).'_'.$id;
    }
}
