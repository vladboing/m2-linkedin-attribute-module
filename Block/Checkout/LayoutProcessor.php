<?php declare(strict_types=1);

namespace Elogic\Linkedin\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class LayoutProcessor implements LayoutProcessorInterface
{
    public function process($jsLayout)
    {
        $customAttributeCode = 'linkedin_profile';
        $customField = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
            ],
            'dataScope' => 'shippingAddress.custom_attributes' . '.' . $customAttributeCode,
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

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$customAttributeCode] = $customField;

        return $jsLayout;
    }

    public function getLinkedinVisibility()
    {
        $visibility = true;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attribute = $objectManager->create('Magento\Eav\Model\AttributeRepository');
        $linkedinProfileIsVisible = $attribute->get('customer', 'linkedin_profile')->getIsVisible();
        if ($linkedinProfileIsVisible == 0) {
            $visibility = false;
        }

        return $visibility;
    }

    public function getLinkedinIsRequired()
    {
        $required = false;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attribute = $objectManager->create('Magento\Eav\Model\AttributeRepository');
        $linkedinProfileIsRequired = $attribute->get('customer', 'linkedin_profile')->getIsRequired();
        if ($linkedinProfileIsRequired == 1) {
            $required = true;
        }

        return $required;
    }
}
