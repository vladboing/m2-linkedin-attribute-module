<?php declare(strict_types=1);

namespace Elogic\Linkedin\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Eav\Model\AttributeRepository;

class LayoutProcessor implements LayoutProcessorInterface
{
    protected const ATTRIBUTE_CODE = 'linkedin_profile';
    protected const NO = 0;
    protected const YES = 1;
    protected const VISIBLE = true;
    protected const NOT_VISIBLE = false;
    protected const REQUIRED = true;
    protected const NOT_REQUIRED = false;
    protected $attributeRepository;

    /**
     * LayoutProcessor constructor.
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * LayoutProcessor process($jsLayout) method.
     * @param array $jsLayout
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function process($jsLayout)
    {
        $customField = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
            ],
            'dataScope' => 'shippingAddress.custom_attributes' . '.' . self::ATTRIBUTE_CODE,
            'label' => 'Linkedin Profile',
            'provider' => 'checkoutProvider',
            'sortOrder' => 50,
            'validation' => [
                'validate-url' => true,
                'validate-length' => 250,
                'required-entry' => $this->getLinkedinIsRequired(),
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => $this->getLinkedinVisibility(),
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][self::AttributeCode] = $customField;

        return $jsLayout;
    }

    /**
     * Discovering if the Linkedin Profile field is a visible field.
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkedinVisibility()
    {
        $visibility = self::VISIBLE;
        $linkedinProfileIsVisible = $this->attributeRepository->get('customer', 'linkedin_profile')->getIsVisible();
        if ($linkedinProfileIsVisible == self::NO) {
            $visibility = self::NOT_VISIBLE;
        }

        return $visibility;
    }

    /**
     * Discovering if the Linkedin Profile is a required field.
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getLinkedinIsRequired()
    {
        $required = self::NOT_REQUIRED;
        $linkedinProfileIsRequired = $this->attributeRepository->get('customer', 'linkedin_profile')->getIsRequired();
        if ($linkedinProfileIsRequired == self::YES) {
            $required = self::REQUIRED;
        }

        return $required;
    }
}
