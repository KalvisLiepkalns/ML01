<?php
declare(strict_types=1);

namespace Magebit\PageListWidget\Block\Widget;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

class PageList extends Template implements BlockInterface
{
    /**
     * @var string $_template
     * @var PageRepositoryInterface $pageRepositoryInterface
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    protected $_template = 'widget/page_list.phtml';
    private PageRepositoryInterface $pageRepositoryInterface;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * Page List Template Initializer
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Context $context
     */
    public function __construct(
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Context $context
    )
    {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct($context);
    }

    /**
     * Returns display mode from widget ``Display Mode`` configuration.
     * @return int 0 or 1
     */
    public function getDisplayMode(): int
    {
        return (int)$this->getData("display_mode");
    }

    /**
     * Returns selected pages from widget ``Selected Pages`` configuration.
     * @return string
     */
    public function getSelectedPages(): string
    {
        return $this->getData("selected_pages");
    }

    /**
     * Get the widget title.
     * @return string
    */
    public function getTitle(): string
    {
        return $this->getData("title");
    }
    /**
     * Get relevant CMS pages.
     * @return PageInterface[]
     */
    private function getCmsPages(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        try {
            return $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
        } catch (LocalizedException $e) {
            $this->_logger->critical($e->getMessage());
        }

        return [];
    }

    /**
     * Returns PageInterface list depending on the display mode.
     * @return PageInterface[]
     */
    public function getPageList(): array
    {
        $pages = $this->getCmsPages();

        return $this->getDisplayMode() ?
            $this->getSpecificPages($pages) : $pages;
    }

    /**
     * Returns only the selected pages.
     * @param PageInterface[] $pages
     * @return PageInterface[]
     */
    private function getSpecificPages(array $pages): array
    {
        $pageIds = explode(",", $this->getSelectedPages());
        $filteredPages = [];

        foreach ($pageIds as $pageId) {
            foreach ($pages as $page) {
                if ($pageId == $page->getId()) $filteredPages[] = $page;
            }
        }

        return $filteredPages;
    }
}
