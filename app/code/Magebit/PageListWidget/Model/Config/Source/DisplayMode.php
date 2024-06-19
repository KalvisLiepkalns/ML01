<?php
namespace Magebit\PageListWidget\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Display mode source model for Page List Widget.
 */
class DisplayMode implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('All Pages')],
            ['value' => '1', 'label' => __('Specific Pages')]
        ];
    }
}
