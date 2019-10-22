<?php declare(strict_types=1);

namespace Elogic\Linkedin\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InstallPatch implements DataPatchInterface
{
    /** @var ModuleDataSetupInterface */
    protected $moduleDataSetup;

    /** @var CustomerSetupFactory */
    protected $customerSetupFactory;

    /** @var AttributeSetFactory */
    protected $attributeSetFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType(Customer::ENTITY);
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            'linkedin_profile',
            [
                'type'         => 'text',
                'label'        => 'Linkedin Profile',
                'input'        => 'text',
                'validate_rules' => '{"max_text_length":250, "input_validation":"url"}',
                'length' => 250,
                'required'     => false,
                'visible'      => true,
                'user_defined' => true,
                'position'     => 90,
                'system'       => 0,
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'linkedin_profile');

        $attribute->addData(
            [
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => [
                    'adminhtml_customer',
                    'adminhtml_checkout',
                    'adminhtml_customer_address',
                    'customer_account_create',
                    'customer_account_edit',
                    'customer_register_address'
                    ]
            ]
        );

        $attribute->save();
    }

    /**
     * {@inheritDoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getAliases()
    {
        return [];
    }
}
