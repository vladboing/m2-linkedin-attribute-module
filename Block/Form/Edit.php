<?php declare(strict_types=1);

namespace Elogic\Linkedin\Block\Form;

class Edit extends \Magento\Customer\Block\Form\Edit
{
    public function getLinkedinVisibility()
    {
        $visibility = null;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attribute = $objectManager->create('Magento\Eav\Model\AttributeRepository');
        $linkedinProfileIsVisible = $attribute->get('customer', 'linkedin_profile')->getIsVisible();
        if ($linkedinProfileIsVisible == 0) {
            $visibility = 'hidden';
        }
        return $visibility;
    }

    public function getLinkedinIsRequired()
    {
        $required = null;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attribute = $objectManager->create('Magento\Eav\Model\AttributeRepository');
        $linkedinProfileIsRequired = $attribute->get('customer', 'linkedin_profile')->getIsRequired();
        if ($linkedinProfileIsRequired == 1) {
            $required = 'required';
        }
        return $required;
    }
}
