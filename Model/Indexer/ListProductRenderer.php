<?php
namespace Zolat\FastCategoryPages\Model\Indexer;

class ListProductRenderer
{
    const BATCH_SIZE = 500;

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
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $storeEmulation;
    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    private $visibility;


    /**
     * ListProductRenderer constructor.
     *
     * @param \Magento\Framework\View\Element\BlockFactory                 $blockFactory
     * @param \Zolat\FastCategoryPages\Model\Cache\MultiLoadCacheInterface $multiLoadCache
     * @param \Zolat\FastCategoryPages\Model\Cache\ProductKeyInterface     $productKey
     * @param \Magento\Catalog\Model\ProductRepository                     $productRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder                 $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder                         $filterBuilder
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager
     *
     * @internal param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Zolat\FastCategoryPages\Model\Cache\MultiLoadCacheInterface $multiLoadCache,
        \Zolat\FastCategoryPages\Model\Cache\ProductKeyInterface $productKey,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\App\Emulation $storeEmulation,
        \Magento\Catalog\Model\Product\Visibility $visibility,
        array $data = []
    ) {
        $this->blockFactory = $blockFactory;
        $this->multiLoadCache = $multiLoadCache;
        $this->productKey = $productKey;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->storeManager = $storeManager;
        $this->storeEmulation = $storeEmulation;
        $this->visibility = $visibility;
    }

    /**
     * @param        $ids
     * @param string $mode
     */
    public function renderProducts($ids, $mode = 'grid')
    {
        if (empty($ids)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Bad value was supplied.'));
        }
        foreach ($this->storeManager->getStores() as $store) {
            if($store->getCode() === 'admin' || $store->getCode() == 'default') {
                continue;
            }

            $this->storeEmulation->startEnvironmentEmulation($store->getId());
            $this->storeManager->setCurrentStore($store);
            $idsBatches = array_chunk($ids, self::BATCH_SIZE);
            foreach ($idsBatches as $changedIds) {
                foreach ($this->getProductsFromIds($changedIds)->getItems() as $product)
                {
                    if (in_array($product->getVisibility(),
                        $this->visibility->getVisibleInSiteIds())) {
                        $this->blockFactory->createBlock($this->productRenderBlock,
                            ['product' => $product, 'mode' => $mode])->toHtml();
                    }
                }
            }
            $this->storeEmulation->stopEnvironmentEmulation();
        }
        return $this;
    }

    /**
     * @param $ids
     *
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    private function getProductsFromIds($ids) {
        $search = $this->searchCriteriaBuilder->addFilters(
            [
                $this->filterBuilder->create()
                    ->setField('entity_id')
                    ->setConditionType('in')
                    ->setValue($ids)
            ]
        );
        return $this->productRepository->getList($search->create());
    }
}