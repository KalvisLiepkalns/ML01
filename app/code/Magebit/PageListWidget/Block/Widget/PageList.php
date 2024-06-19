<?php

namespace Magebit\PageListWidget\Block\Widget;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

class PageList extends Template implements BlockInterface
{

    protected $_template = 'widget/page_list.phtml';
    protected PageRepositoryInterface $pageRepositoryInterface;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(PageRepositoryInterface $pageRepositoryInterface, SearchCriteriaBuilder $searchCriteriaBuilder, Context $context)
    {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct($context);
    }

    /**
     * Returns display mode from widget ``Display Mode`` configuration.
     * @return string "0" or "1"
     */
    public function getDisplayMode(): string
    {
        return $this->getData("display_mode");
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
     * @return stirng
    */
    public function getTitle(): string
    {
        return $this->getData("title");
    }

    /**
     * Get relevent CMS pages.
     * @return PageInterface[]
     */
    private function getCmsPages()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $pages = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();

        return $pages;
    }

    /**
     * Generates HTML li and link for passed down ``$page``.
     * @param PageInterface $page
     * @return string
     */
    private function liLink(PageInterface $page) {
        $link = "<a href='". $page->getIdentifier(). "'>". $page->getTitle(). "</a>";
        return "<li>".$link. "</li>";
    }

    /**
     * Returns HTML ul list of pages, depending on the display mode.
     * @return string
     */
    private function getPageList(): string
    {
        $pages = $this->getCmsPages();
        $html = "";

        $html .= "<ul>";
        $html .= $this->getDisplayMode() === "0" ? $this->getAllPages($pages) : $this->getSpecificPages($pages);
        $html .= "</ul>";

        return $html;
    }

    /**
     * Returns HTML, li list of pages, depending on the display mode in Widget ``Specific Pages``.
     * @param PageInterface[] $pages
     * @return string
     */
    private function getSpecificPages($pages): string
    {

        $pageIds = explode(",", $this->getSelectedPages());
        $html = "";

        foreach ($pageIds as $pageId) {
            foreach ($pages as $page) {
                if ($pageId == $page->getId()) {
                    $html .= $this->liLink($page);
                }
            }
        }

        return $html;
    }

    /**
     * Returns HTML, li list of cms pages, depending on the display mode in Widget ``All Pages``.
     * @param PageInterface[] $pages
     * @return string
     */
    private function getAllPages($pages): string
    {
        $html = "";

        foreach ($pages as $page) {
            $html .= $this->liLink($page);
        }

        return $html;
    }

    public function toHtml(): string
    {
        $html = "<div>";
        $html .= "<h2>". $this->getTitle(). "</h2>";

        $html .= $this->getPageList();

        $html .= "</div>";

        return $html;
    }
}
