<?php
declare(strict_types=1);

namespace Magebit\PageListWidget\Model\Config\Source;

use Exception;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

class CmsPages implements OptionSourceInterface
{

    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private PageRepositoryInterface $pageRepositoryInterface;

    /**
     * CMS Pages Initializer
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Return array of options as value-label pairs of CMS Pages
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray(): array
    {
        $options = [];
        $pages = $this->getPages();

        foreach ($pages as $page) {
            $options[] = [
                'value' => $page->getId(),
                'label' => $page->getTitle(),
            ];
        }

        return $options;
    }

    /**
     * Returns all CMS pages.
     * @return LocalizedException|Exception|PageInterface[]
     */
    public function getPages(): LocalizedException|Exception|array
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        try {
            $collection = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
        } catch (LocalizedException $e) {
            return $e;
        }

        return $collection;
    }
}
