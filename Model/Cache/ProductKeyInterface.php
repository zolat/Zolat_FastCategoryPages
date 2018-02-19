<?php
namespace Zolat\FastCategoryPages\model\cache;

interface ProductKeyInterface
{
    /**
     * @param /Magento/Catalog/Product$product
     *
     * @return string
     */
    public function key($product);


    /**
     * @param $productCollection
     *
     * @return string[]
     */
    public function keyAll($productCollection);
}