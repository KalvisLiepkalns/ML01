<?php

declare(strict_types=1);

namespace MageMastery\FirstLayout\Controller\Page;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Template;

class View implements ActionInterface
{
    private PageFactory $resultPageFactory;

    public function __construct(PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute(): Page
    {
        $page = $this->resultPageFactory->create();

        /** @var AbstractBlock $block  */
        $block = $page->getLayout()->getBlock("magemastery.first.layout.example");
        $block->setData('user', 'Data from controller: Walter Hartwell White');

        return $page;
    }
}
