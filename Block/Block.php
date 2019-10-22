<?php declare(strict_types=1);

namespace Elogic\Linkedin\Block;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Framework\View\Element\Template;

class Block extends Template
{
    /**
     * @var ShippingInformationInterface
     */
    private $_addressInformation;

    public function __construct(
        Template\Context $context,
        ShippingInformationInterface $addressInformation,
        array $data = []
    ) {
        $this->_addressInformation = $addressInformation;
        parent::__construct($context, $data);
    }

    public function getLinkedinProfile()
    {
        $extAttributes = $this->_addressInformation->getExtensionAttributes();
        return $extAttributes->getCustomField();
    }
}
