<?php declare(strict_types=1);

namespace Elogic\Linkedin\Block\Form;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Eav\Model\AttributeRepositoryFactory;

class Edit extends \Magento\Customer\Block\Form\Edit
{
    protected const No = 0;
    protected const Yes = 1;
    protected const NotVisible = null;
    protected const NotRequired = null;
    protected $attributeRepository;

    /**
     * Edit constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param AttributeRepositoryFactory $attributeRepositoryFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        AttributeRepositoryFactory $attributeRepositoryFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        array $data = []
    ) {
        $this->attributeRepository = $attributeRepositoryFactory;
        parent::__construct($context, $customerSession, $subscriberFactory, $customerRepository, $customerAccountManagement, $data);
    }

    /**
     * Discovering if the Linkedin Profile field is a visible field.
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkedinVisibility()
    {
        $visibility = self::NotVisible;
        $linkedinProfileIsVisible = $this->attributeRepository->create()->get('customer', 'linkedin_profile')->getIsVisible();
        if ($linkedinProfileIsVisible == self::No) {
            $visibility = 'hidden';
        }
        return $visibility;
    }

    /**
     * Discovering if the Linkedin Profile field is a required field.
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkedinIsRequired()
    {
        $required = self::NotRequired;
        $linkedinProfileIsRequired = $this->attributeRepository->create()->get('customer', 'linkedin_profile')->getIsRequired();
        if ($linkedinProfileIsRequired == self::Yes) {
            $required = 'required';
        }
        return $required;
    }
}
