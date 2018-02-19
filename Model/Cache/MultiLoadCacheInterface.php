<?php
namespace Zolat\FastCategoryPages\Model\Cache;

interface MultiLoadCacheInterface
{
    /**
     * @param $keys
     *
     * @return string[]
     */
    public function multiLoad($keys);
}