<?php

namespace Intahwebz;

trait Cachable {

    function getCacheKeyName($id) {

        return get_class($this).'_'.$id;
    }
}

 