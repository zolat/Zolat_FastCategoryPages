<?php
namespace Zolat\FastCategoryPages\Model\Cache;

interface MultiLoadCacheInterface
{
    /**
     * @param $keys
     *
     * @return string[]
     */
    public function load($keys);
}