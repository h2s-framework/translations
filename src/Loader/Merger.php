<?php

namespace Siarko\Translations\Loader;

use Siarko\ConfigFiles\Api\ConfigMergerInterface;

class Merger implements ConfigMergerInterface
{

    /**
     * Merge two assoc arrays
     * @param array $base
     * @param array $override
     * @return array
     */
    public function merge(array $base, array $override): array
    {
        foreach ($override as $item) {
            if(count($item) < 2){ continue; }
            $base = array_merge($base, [$item[0] => $item[1]]);
        }
        return $base;
    }
}