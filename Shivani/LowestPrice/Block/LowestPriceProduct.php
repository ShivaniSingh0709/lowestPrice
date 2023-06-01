<?php
namespace Shivani\LowestPrice\Block;

class LowestPriceProduct extends \Magento\Framework\View\Element\Template
{
    const DAYS_CHECK = 30;
    protected $lowestFactory;
    protected $_registry;
    protected $collectionFactory;
    protected $rule;
    protected $currencyPrice;
    protected $scopeConfig;
    protected $storeManager;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,   
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory ,
        \Magento\CatalogRule\Model\Rule $rule,
        \Magento\Framework\Pricing\Helper\Data $currencyPrice,
        \Shivani\LowestPrice\Model\LowestFactory $lowestFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->lowestFactory = $lowestFactory;
        $this->scopeConfig = $scopeConfig;
        $this->_registry = $registry;
        $this->collectionFactory = $collectionFactory;
        $this->rule = $rule ; 
        $this->currencyPrice =$currencyPrice;
        $this->storeManager = $storeManager;
       
        parent::__construct($context, $data);

    }

    public function getCurrentProduct()
    {        

        return $this->product;
    }  
    public function getCurrencySymbol($price)
    {        

        return $this->currencyPrice->currency($price, true, false);
    }
    public function getCatalogPrice($product, $regularPrice)
    {        
        
        return $this->rule->calcProductPriceRule($product, $regularPrice);
        
    }  
   
    public function productData()
    {
       
       $model = $this->lowestFactory->create();
       $data= $model->getCollection();

       $productId = $this->product->getId();

    //    $daysCheck = $this->scopeConfig->getValue("lowest_price/general/days_check",
    //     \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getStoreId());

       $currentDate = date("Y-m-d");
        
       $date = self::DAYS_CHECK;
       
       $lastMonthDate =Date('y-m-d', strtotime('-'.$date.' days'));

        $savedProductId = $this->lowestFactory->create()->getCollection()
             ->addFieldToFilter('product_id',array('eq'=>  $productId))
             ->addFieldToFilter(
                ['date','date'],
                [
                    ['gteq' =>$lastMonthDate],
                    ['lteq' =>$currentDate]
                ]
                );
         
         $savedProductId->getSelect()->columns('MIN(lowest_total_price) as lowest_total_price');

                        $price = $savedProductId->load()->getData();
                         $lowestPrice = $price[0]['lowest_total_price'];
                         var_dump($lowestPrice);die;

       return $lowestPrice ;
    }
    public function setProduct($product){

        $this->product = $product;

        return $this;
    }
    public function lowestPriceLabel()
    {

        $lowestPriceLabel = $this->scopeConfig->getValue("lowest_price/general/lowest_price_label",
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getStoreId());
        
        return $lowestPriceLabel;
        
    }

}
