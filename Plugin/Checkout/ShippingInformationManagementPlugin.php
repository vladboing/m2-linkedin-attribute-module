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
     * @param ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $shippingInformation
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $shippingInformation
    ) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $session = $objectManager->get('Magento\Customer\Model\Session');
        $extensionAttributes = $shippingInformation->getShippingAddress()->getExtensionAttributes();
        if ($session->isLoggedIn()) {
            $linkedinProfile = $session->getCustomerData()->getCustomAttribute('linkedin_profile')->getValue();
        } else {
            $linkedinProfile = $extensionAttributes->getLinkedinProfile();
        }
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setLinkedinProfile($linkedinProfile);
    }
}
