<?php

namespace MageMastery\FirstWidget\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Psr\Log\LoggerInterface;

class YesNo implements OptionSourceInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function toOptionArray()
    {
        $this->logger->debug("MageMastery_FirstWidget::toOptionArray");

        $options = [
            ["value" => "1", "label" => __('Yes')],
            ["value" => "0", "label" => __('No')],
        ];

        $this->logger->debug("YesNo options: ".json_encode($options));

        return $options;
    }
}
