<?php declare(strict_types=1);

namespace Elogic\Linkedin\Block\Form;

use Magento\Eav\Model\AttributeRepositoryFactory;

class Register extends \Magento\Customer\Block\Form\Register
{
    protected const NO = 0;
    protected const YES = 1;
    protected const NOT_VISIBLE = null;
    protected const NOT_REQUIRED = null;
    protected $attributeRepository;

    /**
     * Register constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Directory\Helper\Data $directoryHelper
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param AttributeRepositoryFactory $attributeRepositoryFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Url $customerUrl,
        AttributeRepositoryFactory $attributeRepositoryFactory,
        array $data = []
    ) {
        $this->attributeRepository = $attributeRepositoryFactory;
        parent::__construct($context, $directoryHelper, $jsonEncoder, $configCacheType, $regionCollectionFactory, $countryCollectionFactory, $moduleManager, $customerSession, $customerUrl, $data);
    }

    /**
     * Customized \Magento\Customer\Block\Form\Register getFormData() method.
     * @return \Magento\Framework\DataObject|mixed
     */
    public function getFormData()
    {
        $data = $this->getData('form_data');
        if ($data === null) {
            $formData = $this->_customerSession->getCustomerFormData(true);
            $data = new \Magento\Framework\DataObject();
            if ($formData) {
                $data->addData($formData);
                $linkedinProfile = ['linkedin_profile' => $this->_customerSession->getLinkedinProfile()];
                $data->addData($linkedinProfile);
                $data->setCustomerData(1);
            }
            if (isset($data['region_id'])) {
                $data['region_id'] = (int)$data['region_id'];
            }
            $this->setData('form_data', $data);
        }
        return $data;
    }

    /**
     * Discover if the Linkedin Profile field is a visible field.
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkedinVisibility()
    {
        $visibility = self::NOT_VISIBLE;
        $linkedinProfileIsVisible = $this->attributeRepository->create()->get('customer', 'linkedin_profile')->getIsVisible();
        if ($linkedinProfileIsVisible == self::NO) {
            $visibility = 'hidden';
        }

        return $visibility;
    }

    /**
     * Discover if the Linkedin Profile field is a required field.
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkedinIsRequired()
    {
        $required = self::NOT_REQUIRED;
        $linkedinProfileIsRequired = $this->attributeRepository->create()->get('customer', 'linkedin_profile')->getIsRequired();
        if ($linkedinProfileIsRequired == self::YES) {
            $required = 'required';
        }

        return $required;
    }
}
