<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPriceHyva\Block;

use FeWeDev\Base\Variables;
use Infrangible\CatalogProductPrice\Helper\FinalPrice;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\AwareInterface;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Item extends Template implements AwareInterface
{
    /** @var Variables */
    protected $variables;

    /** @var FinalPrice */
    protected $finalPriceHelper;

    /** @var ProductInterface */
    private $product;

    public function __construct(
        Template\Context $context,
        Variables $variables,
        FinalPrice $finalPriceHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );

        $this->variables = $variables;
        $this->finalPriceHelper = $finalPriceHelper;
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
    }

    public function hasFinalPriceLabel(): bool
    {
        $product = $this->getProduct();

        if ($product instanceof SaleableInterface) {
            $label = $this->finalPriceHelper->getLabel(
                $product,
                ''
            );

            return ! $this->variables->isEmpty($label);
        }

        return false;
    }
}
