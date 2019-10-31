<?php declare(strict_types=1);

namespace Elogic\Linkedin\Plugin\Checkout;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Customer\Model\SessionFactory;
use Magento\Quote\Model\QuoteRepository;

class ShippingInformationManagementPlugin
{
    protected $quoteRepository;
    protected $session;

    /**
     * ShippingInformationManagementPlugin constructor.
     * @param QuoteRepository $quoteRepository
     * @param SessionFactory $sessionFactory
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        SessionFactory $sessionFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->session = $sessionFactory;
    }

    /**
     * Method executing before saving customer address information on checkout.
     * @param ShippingInformationManagement $subject
     * @param $cartId
     * @param ShippingInformationInterface $shippingInformation
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $shippingInformation
    ) {
        if ($this->session->create()->isLoggedIn()) {
            $linkedinProfile = $this->session->create()->getCustomerData()->getCustomAttribute('linkedin_profile')->getValue();
        } else {
            $linkedinProfile = $shippingInformation->getShippingAddress()->getExtensionAttributes()->getLinkedinProfile();
        }
        $this->quoteRepository->getActive($cartId)->setLinkedinProfile($linkedinProfile);
    }
}
