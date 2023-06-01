<?php

namespace Shivani\LowestPrice\Setup;
    

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
     */



    public function __construct( EavSetupFactory $eavSetupFactory) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.4', '<')) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        
	 $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'lowest_price',
            [
                'type' =>'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Lowest Price',
                'input' => 'price',
                'sort_order' => '30',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' =>false,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => 'simple,configurable',
                'group' =>'advanced-pricing'
            ]
        );
    }
	 $setup->endSetup();    
    }
    
    
}

