<?php declare(strict_types=1);

namespace Elogic\Linkedin\Plugin\Checkout;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Quote\Model\QuoteRepository;

class ShippingInformationManagementPlugin
{
    protected $quoteRepository;

    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }
    
    /**
     * @param ShippingInformationInterface $shippingInformation
     * @param $cartId
     * @param ShippingInformationManagement $subject
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $shippingInformation
    ) {
        $extensionAttributes = $shippingInformation->getShippingAddress()->getExtensionAttributes();
        $linkedinProfile = $extensionAttributes->getLinkedinProfile();
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setLinkedinProfile($linkedinProfile);
    }
}
