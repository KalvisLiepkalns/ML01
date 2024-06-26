<?php
declare(strict_types=1);
namespace Magebit\PageListWidget\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Display mode source model for Page List Widget.
 */
class DisplayMode implements OptionSourceInterface
{
    /**
     * Return array of options as value-label pairs to determine the display mode.
     *
     * @return array Format: array(array('value' => '0', 'label' => 'All pages'), ...)
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '0', 'label' => __('All Pages')],
            ['value' => '1', 'label' => __('Specific Pages')]
        ];
    }
}
