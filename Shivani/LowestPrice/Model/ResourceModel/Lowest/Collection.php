<?php
namespace Shivani\LowestPrice\Model\ResourceModel\Lowest;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'catalog_product_lowest_total_price_log_collection';
    protected $_eventObject = 'lowest_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Pixelmechanics\LowestPrice\Model\Lowest', 'Pixelmechanics\LowestPrice\Model\ResourceModel\Lowest');
    }

}
