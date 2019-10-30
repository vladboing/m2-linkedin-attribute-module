<?php declare(strict_types=1);

namespace Elogic\Linkedin\Block\Form;

class Register extends \Magento\Customer\Block\Form\Register
{
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
}
