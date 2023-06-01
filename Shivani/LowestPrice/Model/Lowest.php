<?php
namespace Pixelmechanics\LowestPrice\Model;
class Lowest extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'catalog_product_lowest_total_price_log';

    protected $_cacheTag = 'catalog_product_lowest_total_price_log';

    protected $_eventPrefix = 'catalog_product_lowest_total_price_log';

    protected function _construct()
    {
        $this->_init('Pixelmechanics\LowestPrice\Model\ResourceModel\Lowest');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}