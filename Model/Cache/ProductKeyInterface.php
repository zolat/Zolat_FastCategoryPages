<?php
namespace Zolat\FastCategoryPages\Model\Cache;

interface ProductKeyInterface
{
    /**
     * @param /Magento/Catalog/Product$product
     *
     * @return string
     */
    public function key($product, $mode);


    /**
     * @param $productCollection
     *
     * @return string[]
     */
    public function keyAll($productCollection, $mode);
}