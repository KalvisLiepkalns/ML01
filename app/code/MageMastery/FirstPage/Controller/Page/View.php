<?php

declare(strict_types = 1);

namespace MageMastery\FirstPage\Controller\Page;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class View implements ActionInterface
{
    /**
     * @var JsonFactory PageFactory
     */
    private JsonFactory $resultJsonFactory;

    /**
     * @return Json
     */

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData(['json_data' => 'come from json'],);
    }


    /**
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
    }


}
