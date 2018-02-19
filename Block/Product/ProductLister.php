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
    /**
     * @var \Zolat\FastCategoryPages\Model\Cache\MultiLoadCacheInterface
     */
    private $multiLoadCache;
    /**
     * @var \Zolat\FastCategoryPages\Model\Cache\ProductKeyInterface
     */
    private $productKey;

    /**
     * ProductLister constructor.
     *
     * @param \Magento\Catalog\Block\Product\Context                       $context
     * @param \Magento\Framework\Data\Helper\PostHelper                    $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver                        $layerResolver
     * @param CategoryRepositoryInterface                                  $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data                           $urlHelper
     * @param \Magento\Framework\View\Element\BlockFactory                 $blockFactory
     * @param \Zolat\FastCategoryPages\Model\Cache\MultiLoadCacheInterface $multiLoadCache
     * @param \Zolat\FastCategoryPages\Model\Cache\ProductKeyInterface     $productKey
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Zolat\FastCategoryPages\Model\Cache\MultiLoadCacheInterface $multiLoadCache,
        \Zolat\FastCategoryPages\Model\Cache\ProductKeyInterface $productKey,
        array $data = []
    ) {
        parent::__construct($context, $postDataHelper, $layerResolver,
            $categoryRepository, $urlHelper, $data);
        $this->blockFactory = $blockFactory;
        $this->multiLoadCache = $multiLoadCache;
        $this->productKey = $productKey;
    }

    /**
     * @return string
     */
    public function renderProducts()
    {
        $cached = $this->multiLoadCache->multiLoad($this->productKey->keyAll($this->getLoadedProductCollection(), $this->getMode()));
        $html = '';
        $i = 0;
        foreach ($this->getLoadedProductCollection() as $product) {
            if(isset($cached[$i])) {
                $html .= $cached[$i];
            } else {
                $html .= $this->blockFactory->createBlock($this->productRenderBlock, ['product' => $product, 'mode' => $this->getMode()])->toHtml();
            }
            $i++;
        }
        return $html;
    }
}