<?php
namespace Shivani\LowestPrice\Helper;
use Magento\Framework\View\LayoutInterface;


class Data extends \Magento\Framework\App\Helper\AbstractHelper

{
    protected $storeManager;
    protected $scopeConfig;

    public function __construct(
        LayoutInterface $layout,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ){
        $this->layout = $layout;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;

    }

       public function getLowestPriceText($product)
       {
        
        $renderer = $this->layout->createBlock(
            'Shivani\LowestPrice\Block\LowestPriceProduct'
        )->setTemplate("Shivani_LowestPrice::lowest_price.phtml")->setProduct($product)->toHtml();
        return $renderer;

       }
       public function getModuleEnable()
       {
        $isModuleEnable = $this->scopeConfig->getValue("lowest_price/general/enable",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->storeManager->getStore()->getStoreId());
            
            return $isModuleEnable;
       }
}
