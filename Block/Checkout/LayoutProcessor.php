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
                // customScope is used to group elements within a single form (e.g. they can be validated separately)
                'customScope' => 'shippingAddress.custom_attributes',
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'tooltip' => [
                    'description' => 'Linkedin profile',
                ],
            ],
            'dataScope' => 'shippingAddress.custom_attributes' . '.' . $customAttributeCode,
            'label' => 'Linkedin Profile',
            'provider' => 'checkoutProvider',
            'sortOrder' => 0,
            'validation' => [
                'required-entry' => false
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'value' => '' // value field is used to set a default value of the attribute
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'][$customAttributeCode] = $customField;

        return $jsLayout;
    }
}
