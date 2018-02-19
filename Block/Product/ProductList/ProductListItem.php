<?php
namespace Zolat\FastCategoryPages\Block\Product\ProductList;

class ProductListItem extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var \Zolat\FastCategoryPages\Block\Product\ProductLister
     */
    private $productLister;

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
        $mode = 'grid',
        $template = 'Zolat_FastCategoryPages::product/list/item.phtml',
        array $data = []
    )
    {
        $this->setTemplate($template);
        parent::__construct($context, $data);
        $this->product = $product;
        $this->setData('mode', $mode);
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct() {
        return $this->product;
    }
}