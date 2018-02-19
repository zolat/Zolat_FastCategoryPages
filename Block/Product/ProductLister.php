<?php
namespace Zolat\FastCategoryPages\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class ProductLister extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $productRenderBlock = 'Zolat\FastCategoryPages\Block\Product\ProductList\ProductListItem';
    /**
     * @var \Magento\Framework\View\Element\BlockFactory
     */
    private $blockFactory;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        array $data = []
    ) {
        parent::__construct($context, $postDataHelper, $layerResolver,
            $categoryRepository, $urlHelper, $data);
        $this->blockFactory = $blockFactory;
    }

    public function renderProducts()
    {
        $html = '';
        foreach ($this->getLoadedProductCollection() as $product) {
            $html .= $this->blockFactory->createBlock($this->productRenderBlock, ['product' => $product, 'mode' => $this->getMode()])->toHtml();
        }
        return $html;
    }
}