<?php
namespace Zolat\FastCategoryPages\Block\Product\ProductList;

class ProductListItem extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var \Zolat\FastCategoryPages\Model\Cache\ProductKeyInterface
     */
    private $productKey;


    /**
     * ProductListItem constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context               $context
     * @param \Magento\Catalog\Model\Product                       $product
     * @param \Zolat\FastCategoryPages\Block\Product\ProductLister $productLister
     * @param array                                                $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Product $product,
        \Zolat\FastCategoryPages\Model\Cache\ProductKeyInterface $productKey,
        $mode = 'grid',
        $template = 'Zolat_FastCategoryPages::product/list/item.phtml',
        $cache_lifetime = 3600,
        array $data = []
    )
    {
        $this->setTemplate($template);
        parent::__construct($context, $data);
        $this->product = $product;
        $this->setData('mode', $mode);
        $this->productKey = $productKey;
        $this->setData('cache_lifetime', $cache_lifetime);
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct() {
        return $this->product;
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return $this->productKey->key($this->getProduct(), $this->getMode());
    }

    /**
     * @return array
     */
    public function getIdentities() {
        return $this->getProduct()->getIdentities();
    }

    /**
     * We know it's not in cache if was not loaded in collection load
     * @return bool
     */
    protected function _loadCache()
    {
        return false;
    }

}