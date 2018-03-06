<?php
namespace Zolat\FastCategoryPages\Model\Cache;

class MultiLoadRedisCache extends \Cm_Cache_Backend_Redis implements \Zolat\FastCategoryPages\Model\Cache\MultiLoadCacheInterface
{
    private $prefix;

    /**
     * MultiLoadRedisCache constructor.
     *
     * @param \Magento\Framework\App\DeploymentConfig    $deploymentConfig
     * @param \Magento\Framework\App\Cache\Frontend\Pool $pool
     */
    public function __construct(
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Magento\Framework\App\Cache\Frontend\Pool $pool
    )
    {
        parent::__construct($deploymentConfig->get('cache/frontend/default/backend_options'));
        $this->prefix = $pool->get('default')->getLowLevelFrontend()->getOption('cache_id_prefix');
    }

    /**
     * @param $keys
     *
     * @return array|bool
     */
    public function multiLoad($keys)
    {
        $this->applyCachePrefix($keys);
        $this->_redis->multi();
        foreach ($keys as $key) {
            $this->_redis->hGet(self::PREFIX_KEY.$key, self::FIELD_DATA);
        }
        $result = $this->_redis->exec();
        if(!$result) {
            return FALSE;
        }
        foreach ($result as &$item) {
            $item = $this->_decodeData($item);
        }
        return $result;
    }

    /**
     * @param $keys
     *
     * @return void
     */
    private function applyCachePrefix(&$keys)
    {
        if($this->prefix) {
            foreach ($keys as &$key) {
                $key = $this->prefix.$key;
            }
        }
    }
}