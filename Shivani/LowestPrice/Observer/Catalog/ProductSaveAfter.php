<?php
/**
 * Created an Observer that run after product save in admin
 * @author - Shivani
 * @date - 15.09.2022
 * @link - https://pixelmechanics.atlassian.net/browse/ENRUSM-46
 */

declare(strict_types=1);

namespace Shivani\LowestPrice\Observer\Catalog;

class ProductSaveAfter implements \Magento\Framework\Event\ObserverInterface
{

    protected $scopeConfig;
    protected $lowestFactory;
    protected $collectionFactory;
    protected $storeManager;
    private $originalPrice;
    private $productId;
    private $currentLowestPrice;
    private $notSavedLowestPrice;
    private $specialPrice;
    private $origSpecialPrice;
    /**
     * Execute observer
     *
     */

    public function __construct(
        \Shivani\LowestPrice\Model\LowestFactory $lowestFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->lowestFactory = $lowestFactory;
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->originalPrice = null;
        $this->productId     = null;
        $this->currentLowestPrice =null;
        $this->specialPrice =null;
        $this->notSavedLowestPrice =null;
        $this->origSpecialPrice = null;

    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {


        #to check module is enable or disable
        $isModuleEnable = $this->scopeConfig->getValue("lowest_price/general/enable",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,$this->storeManager->getStore()->getStoreId());


        $_product               = $observer->getProduct();  
        $this->origSpecialPrice = $_product->getOrigData('special_price');
        // var_dump($this->origSpecialPrice);die;
        $specialToDate   = $_product->getSpecialToDate();
        $specialFromDate = $_product->getSpecialFromDate();
            
        // getting the product details

        $this->productId     = $_product->getId();              // product id
        $this->originalPrice = $_product->getPrice();           // Original price of product
        $this->specialPrice  = $_product->getSpecialPrice();    // special price on product
        $lowestPrice         = $_product->getLowestPrice();     // lowest price on product
        $finalPrice          = $this->originalPrice -  $this->specialPrice;
      
        $model = $this->lowestFactory->create();

        # fetching data from catalog_product_lowest_total_price_log table

        $currentDate = date("Y-m-d");

        $savedProductDetail = $this->lowestFactory->create()->getCollection()
            ->addFieldToFilter('product_id',array('eq'=>  $this->productId))
            ->addFieldToFilter('date',array('eq'=> $currentDate))->getFirstItem()->debug();


        try {
            if ($isModuleEnable)
            {
                if ( $this->specialPrice && ($finalPrice < $this->originalPrice)) {

                    if($this->origSpecialPrice >= $this->specialPrice){

                        return $observer;
                    }
                   
                }
                /*
                discontinous case
                */
                elseif($this->origSpecialPrice > 0)
                {
                    if ($savedProductDetail['id']) {

                    $this->updateInCatalogLowestPriceTable();

                    }

                }
                else{

                    if (!$savedProductDetail) {

                        $this->insertInCatalogLowestPriceTable();

                    }
                }

            }
        }
        catch(\Exception $e){

            echo('Message: ' .$e->getMessage());
        }
        return $observer;
    }
    
    public function insertInCatalogLowestPriceTable()
    {

        $currentDate = date("Y-m-d");
        $originalPrice = $this->originalPrice;
        $productId     = $this->productId;

        $model = $this->lowestFactory->create();

        $data = [
                'product_id' => $productId,
                'date'      => $currentDate,
                'lowest_total_price' => $originalPrice
            ];
        //   print_r($data);die;
        $model->setData($data)->save();
         return true;
    }
    public function updateInCatalogLowestPriceTable()
    {
        $currentDate = date("Y-m-d");
        $productId     = $this->productId;
        $savedProductDetail = $this->lowestFactory->create()->getCollection()
            ->addFieldToFilter('product_id',array('eq'=>  $productId))
            ->addFieldToFilter('date',array('eq'=> $currentDate))->getFirstItem()->debug();
        $id =   $savedProductDetail['id'];
        try {
            $model = $this->lowestFactory->create();
            $model->load($id);
            $model->setLowestTotalPrice($this->origSpecialPrice);
            // print_r($this->origSpecialPrice); die;
            $model->save();
        }
        catch(\Exception $e){

            echo('Message: ' .$e->getMessage());
        }
         return true ;
    }
    public function updateSpecialInCatalogLowestPriceTable()
    {
        $currentDate = date("Y-m-d");
        $productId     = $this->productId;
        $savedProductDetail = $this->lowestFactory->create()->getCollection()
            ->addFieldToFilter('product_id',array('eq'=>  $productId))
            ->addFieldToFilter('date',array('eq'=> $currentDate))->getFirstItem()->debug();
        $id =   $savedProductDetail['id'];
        try {
            $model = $this->lowestFactory->create();
            $model->load($id);
            $model->setLowestTotalPrice($this->originalPrice);
            // print_r($this->origSpecialPrice); die;
            $model->save();
        }
        catch(\Exception $e){

            echo('Message: ' .$e->getMessage());
        }
         return true ;
    }
    

}
