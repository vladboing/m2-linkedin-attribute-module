<?php declare(strict_types=1);

namespace Elogic\Linkedin\Model\Config;

use Magento\Framework\Data\OptionSourceInterface;

class DropdownOptions implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Hidden')],
            ['value' => '1', 'label' => __('Optional')],
            ['value' => '2', 'label' => __('Required')],
        ];
    }
}