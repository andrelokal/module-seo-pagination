<?php

namespace Siscom\SeoPagination\Block;

use Siscom\SeoPagination\Model\ConfigInterface;
use Magento\Catalog\Model\Category;
use Magento\Framework\View\Element\Template;
use Siscom\SeoPagination\Model\Link;

class Pagination extends Template
{
    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    private $layerResolver;

    /**
     * @var
     */
    private $currentCategory;

    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $pager;

    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $currentPager;

    /**
     * @var \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    private $toolbar;
    /**
     * @var ConfigInterface
     */
    private $configModule;


    /**
     * Pagination constructor.
     *
     * @param Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Theme\Block\Html\Pager $pager
     * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $toolbar
     * @param ConfigInterface $configModule
     * @param array $data
     * @param \Magento\Framework\Registry $_registry
     */
    public function __construct(
        Template\Context                                   $context,
        \Magento\Catalog\Model\Layer\Resolver              $layerResolver,
        \Magento\Theme\Block\Html\Pager                    $pager,
        \Magento\Catalog\Block\Product\ProductList\Toolbar $toolbar,
        ConfigInterface                                    $configModule,
        array                                              $data = []
    )
    {
        parent::__construct($context, $data);
        $this->layerResolver = $layerResolver;
        $this->pager = $pager;
        $this->toolbar = $toolbar;
        $this->configModule = $configModule;
    }

    /**
     * @return Category
     */
    public function getCurrentCategory(): Category
    {
        $this->currentCategory = $this->layerResolver->get()->getCurrentCategory();
        return $this->currentCategory;
    }

    /**
     * @return array
     */
    public function getPageHeaderLinks()
    {
        $result = [];
        $currentCat = $this->getPager()->getRequest()->getParam('cat') ?? null;

        if ($this->configModule->isEnable()) {
            $pager = $this->getCurrentPager($currentCat);
            if ($pager->getCurrentPage() > 1) {
                $result[] = new Link('prev', $this->getPreviousPageUrl());
            }

            if ($pager->getCurrentPage() < $pager->getLastPageNum()) {
                $result[] = new Link('next', $pager->getNextPageUrl());
            }
        }

        return $result;
    }

    /**
     * @return \Magento\Theme\Block\Html\Pager
     */
    public function getPager(): \Magento\Theme\Block\Html\Pager
    {
        return $this->pager;
    }

    /**
     * @return \Magento\Theme\Block\Html\Pager
     */
    public function getCurrentPager($currentCat = null): \Magento\Theme\Block\Html\Pager
    {
        if ($currentCat) {
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $objCategory = $_objectManager->create('Magento\Catalog\Model\Category')->load($currentCat);
        } else {
            $objCategory = $this->getCurrentCategory();
        }

        $this->getPager()->setLimit($this->getLimit());

        if (!$this->currentPager) {
            $searchTerm = $this->_request->getParam('q');
            if($searchTerm){
                $productColection = $objCategory->getProductCollection()->addAttributeToFilter(
                    array(
                        array('attribute' => 'name', 'like' => '%' . $searchTerm . '%'),
                        array('attribute' => 'description', 'like' => '%' . $searchTerm . '%'),
                    ));
            }else{
                $productColection = $objCategory->getProductCollection();
            }

            $this->currentPager = $this->getPager()
                ->setCollection(
                    $productColection
                );
            $this->currentPager->setShowPerPage($this->getLimit());
        }

        return $this->currentPager;
    }

    /**
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function getToolbar(): \Magento\Catalog\Block\Product\ProductList\Toolbar
    {
        return $this->toolbar;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->getToolbar()->getLimit();
    }

    /**
     * @return string
     */
    private function getPreviousPageUrl(): string
    {
        $pager = $this->getCurrentPager();
        if ($pager->getCurrentPage() > 2) {
            return $pager->getPreviousPageUrl();
        }
        return $this->getFirstPageUrl();
    }

    /**
     * @return string
     */
    private function getFirstPageUrl(): string
    {
        $pager = $this->getCurrentPager();
        return $pager->getPagerUrl([$pager->getPageVarName() => null]);
    }

}
