<?php
namespace Pixelmechanics\LowestPrice\Model\ResourceModel;


class Lowest extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('catalog_product_lowest_total_price_log', 'id');
    }

}