<?php

namespace MageMastery\FirstWidget\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class SimpleWidget extends Template implements BlockInterface
{

    protected $_template = 'MageMastery_FirstWidget::widget/simple_widget.phtml';

    public function getYesNo() {
        return $this->getData("yes_no");
    }
}
