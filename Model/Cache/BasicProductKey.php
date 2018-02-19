<?php
namespace Zolat\FastCategoryPages\Model\Cache;

use Magento\Store\Model\StoreManagerInterface;

class BasicProductKey implements ProductKeyInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @param /Magento/Catalog/Product$product
     *
     * @return string
     */
    public function key($product, $mode)
    {
        return strtoupper('BLOCK_PLPITEM_'.$mode.'_'.$this->storeManager->getStore()->getId().'_'.$product->getId()); //TODO row id?
    }

    /**
     * @param $productCollection
     *
     * @return string[]
     */
    public function keyAll($productCollection, $mode)
    {
        $result = [];
        $base = 'BLOCK_PLPITEM_'.$mode.'_'.$this->storeManager->getStore()->getId();
        foreach ($productCollection as $product) {
            $result[] = strtoupper($base.'_'.$product->getId());
        }
        return $result;
    }
}