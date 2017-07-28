<?php

namespace Vendic\RegenerateProductUrls\Cron;

use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreRepository;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class Generate
{

    protected $logger;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    protected $_storeRepository;

    protected $_productCollectionFactory;

    /**
     * @var ProductUrlRewriteGenerator
     */
    protected $productUrlRewriteGenerator;

    /**
     * @var UrlPersistInterface
     */
    protected $urlPersist;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepo;

    public function __construct(
        LoggerInterface $logger,
        StoreRepository $storeRepository,
        CollectionFactory $productCollectionFactory,
        State $state,
        ProductRepositoryInterface $productRepo,
        ProductUrlRewriteGenerator $productUrlRewriteGenerator,
        UrlPersistInterface $urlPersist
    )
    {
        $this->logger = $logger;
        $this->_storeRepository = $storeRepository;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->state = $state;
        $this->productRepo = $productRepo;
        $this->productUrlRewriteGenerator = $productUrlRewriteGenerator;
        $this->urlPersist = $urlPersist;
    }

    /**
     * Get array of all store views
     *
     * @return array
     */
    protected function getStoreIds()
    {
        $stores = $this->_storeRepository->getList();

        $storeIds = [];

        foreach ($stores as $store) {
            $storeIds[] = $store["store_id"];
        }

        return (array)$storeIds;

    }

    /**
     * @return mixed
     */
    protected function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        return $collection;
    }

    /**
     * Get array of all product ids
     *
     * @return array
     */
    protected function getProductIds()
    {
        $productIds = [];
        $productCollection = $this->getProductCollection();

        foreach ($productCollection as $product) {
            $productIds[] = $product->getId();
        }

        return (array)$productIds;

    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {

        if (!$this->state->getAreaCode()) {
            $this->state->setAreaCode('adminhtml');
        }

        $pids = $this->getProductIds();
        $stores = $this->getStoreIds();

        foreach ($stores as $store_id) {

            foreach ($pids as $pid) {
                $product = $this->productRepo->getById($pid, false, $store_id);

                $this->urlPersist->deleteByData([
                    UrlRewrite::ENTITY_ID => $product->getId(),
                    UrlRewrite::ENTITY_TYPE => ProductUrlRewriteGenerator::ENTITY_TYPE,
                    UrlRewrite::REDIRECT_TYPE => 0,
                    UrlRewrite::STORE_ID => $store_id
                ]);
                try {
                    $this->urlPersist->replace(
                        $this->productUrlRewriteGenerator->generate($product)
                    );
                } catch (\Exception $e) {
                    $this->logger->info("<error>$pid</error>");
                }
            }

        }

    }
}