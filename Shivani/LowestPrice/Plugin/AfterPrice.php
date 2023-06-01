<?php
namespace Shivani\LowestPrice\Plugin;

use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\LayoutInterface;

/**
 * Class AfterPrice
 * @package Lof\BasePrice\Model\Plugin
 */
class AfterPrice
{
    /**
     * Hold final price code
     *
     * @var string
     */
    const FINAL_PRICE = 'final_price';

    /**
     * Hold tier price code
     *
     * @var string
     */
    const TIER_PRICE = 'tier_price';

    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @var []
     */
    protected $afterPriceHtml = [];

    /**
     * @param LayoutInterface $layout
     */
    public function __construct(
        LayoutInterface $layout
    ){
        $this->layout = $layout;
    }

    /**
     * Plugin for price rendering in order to display after price information
     *
     * @param Render $subject
     * @param $renderHtml string
     * @return string
     */
    public function aroundRender(Render $subject, \Closure $closure, ...$params)
    {
        // run default render first
        $renderHtml = $closure(...$params);
        try{
            // Get Price Code and Product
            list($priceCode, $productInterceptor) = $params;

            $emptyTierPrices = empty($productInterceptor->getTierPrice());
            // If it is final price block and no tier prices exist set additional render
            // If it is tier price block and tier prices exist set additional render
//            if ((static::FINAL_PRICE === $priceCode && $emptyTierPrices) || (static::TIER_PRICE === $priceCode && !$emptyTierPrices)) {
            if ((static::FINAL_PRICE === $priceCode)){
                $renderHtml .= $this->getAfterPriceHtml($productInterceptor);
            }
        } catch (\Exception $ex) {
            // if an error occurs, just render the default since it is preallocated
            return $renderHtml;
        }

        return $renderHtml;
    }

    /**
     * Renders and caches the after price html
     *
     * @return null|string
     */
    protected function getAfterPriceHtml(SaleableInterface $product)
    {
      
        if ($product->getTypeId() == 'configurable') {
            $renderer = $this->layout->createBlock(
                'Pixelmechanics\LowestPrice\Block\LowestPriceProduct'
            )->setTemplate("Pixelmechanics_LowestPrice::configurable/afterPrice.phtml")->setProduct($product)->toHtml();
            return $renderer;
        } else {
            $renderer = $this->layout->createBlock(
                'Pixelmechanics\LowestPrice\Block\LowestPriceProduct'
            )->setTemplate("Pixelmechanics_LowestPrice::lowest_price.phtml")->setProduct($product)->toHtml();
            return $renderer;
        }

        
        

    }
}
