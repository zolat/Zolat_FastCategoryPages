<?php
namespace Zolat\FastCategoryPages\Model\Indexer;

use Magento\Framework\Indexer\CacheContext;

class ProductListHtml implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var \Zolat\FastCategoryPages\Model\Indexer\ListProductRenderer
     */
    private $listProductRenderer;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private $productCollection;

    /**
     * ProductListHtml constructor.
     *
     * @param \Zolat\FastCategoryPages\Model\Indexer\ListProductRenderer $listProductRenderer
     */
    public function __construct(
        \Zolat\FastCategoryPages\Model\Indexer\ListProductRenderer $listProductRenderer,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection
        )
    {
        $this->listProductRenderer = $listProductRenderer;
        $this->productCollection = $productCollection->create();
    }

    /**
     * Execute materialization on ids entities
     *
     * @param int[] $ids
     * @return void
     */
    public function execute($ids)
    {
        $this->listProductRenderer->renderProducts($ids);
        $this->getCacheContext()->registerEntities(\Magento\Catalog\Model\Product::CACHE_TAG, $ids);
    }

    /**
     * Execute full indexation
     *
     * @return void
     */
    public function executeFull()
    {
        $processed = 0;
        do {
            $ids = $this->productCollection->getAllIds(\Zolat\FastCategoryPages\Model\Indexer\ListProductRenderer::BATCH_SIZE, $processed);
            if($ids) {
                $this->listProductRenderer->renderProducts($ids);
            }
            $processed += count($ids);
        } while(count($ids));
    }

    /**
     * Execute partial indexation by ID list
     *
     * @param int[] $ids
     * @return void
     */
    public function executeList(array $ids)
    {
        $this->listProductRenderer->renderProducts($ids);
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     * @return void
     */
    public function executeRow($id)
    {
        $this->listProductRenderer->renderProducts($id);
    }
}
