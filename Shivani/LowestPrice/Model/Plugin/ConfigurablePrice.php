<?php
/*
 * @author:shivani
 * Date:22Feb2023
 * Description: this file get price from helper and create lowest price index and pass price value to that(configurable)
 */
namespace Pixelmechanics\LowestPrice\Model\Plugin;


class ConfigurablePrice
{
    
    protected $_helper;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    protected $_jsonDecoder;

    /**
     * Constructor
     *
     * @param \Pixelmechanics\LowestPrice\Helper\Data $helper
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     */
    public function __construct(
        \Pixelmechanics\LowestPrice\Helper\Data $helper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder
    ){
        $this->_helper = $helper;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_jsonDecoder = $jsonDecoder;
    }

    /**
     * Plugin for configurable price rendering. Iterates over configurable's simples and adds the base price
     * to price configuration.
     *
     * @param \Magento\Framework\Pricing\Render $subject
     * @param $json string
     * @return string
     */
    public function afterGetJsonConfig(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject, $json)
    {
        $config = $this->_jsonDecoder->decode($json);
        /** @var $product \Magento\Catalog\Model\Product */
        foreach ($subject->getAllowProducts() as $product) {

            $lowestPriceText = $this->_helper->getLowestPriceText($product);
            if (empty($lowestPriceText)) {

                // if simple has no configured base price, us at least the base price of configurable
                $lowestPriceText = $this->_helper->getLowestPriceText($subject->getProduct());
            }

            $config['optionPrices'][$product->getId()]['lowestprice_text'] = $lowestPriceText ?? '';
            
        }

        return $this->_jsonEncoder->encode($config);

    }
}
