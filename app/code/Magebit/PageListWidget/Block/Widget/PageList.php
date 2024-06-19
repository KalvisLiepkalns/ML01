<?php

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
     * Simple string HTML tag maker.
     * @param ?string $tag html tag type
     * @param string ...$children passed down children
     * @return string
     */
    private function html(?string $tag, string ...$children): string
    {
        $startTag= $tag ? "<".$tag.">" : "";
        $endTag= $tag ? "</".$tag.">" : "";
        return $startTag . implode($children) . $endTag;
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
     * @return string
    */
    public function getTitle(): string
    {
        return $this->getData("title");
    }

    /**
     * Get relevant CMS pages.
     * @return PageInterface[]
     * @throws LocalizedException
     */
    private function getCmsPages(): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        return $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
    }

    /**
     * Generates HTML li and link for passed down ``$page``.
     * @param PageInterface $page
     * @return string
     */
    private function liLink(PageInterface $page): string
    {
        return $this->html(
            "li",
            "<a href='". $page->getIdentifier(). "'>". $page->getTitle(). "</a>"
        );
    }

    /**
     * Returns HTML ul list of pages, depending on the display mode.
     * @return string
     * @throws LocalizedException
     */
    private function getPageList(): string
    {
        $pages = $this->getCmsPages();

        return $this->html(
            "ul",
            $this->getDisplayMode() === "0" ? $this->getAllPages($pages) : $this->getSpecificPages($pages)
        );
    }

    /**
     * Returns HTML, li list of pages, depending on the display mode in Widget ``Specific Pages``.
     * @param PageInterface[] $pages
     * @return string
     */
    private function getSpecificPages(array $pages): string
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
    private function getAllPages(array $pages): string
    {
        $html = "";

        foreach ($pages as $page) {
            $html .= $this->liLink($page);
        }

        return $html;
    }

    /**
     * @throws LocalizedException
     */
    public function toHtml(): string
    {
        return $this->html(
            "div",
            $this->html("h2", $this->getTitle()),
            $this->html(null, children: $this->getPageList())
        );
    }
}
