<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductPriceHyva\Block;

use FeWeDev\Base\Json;
use Infrangible\CatalogProductPrice\Helper\FinalPrice;
use Infrangible\CatalogProductPrice\Helper\Information;
use Infrangible\CatalogProductPrice\Helper\OldPrice;
use Infrangible\Core\Helper\DataObject;
use Infrangible\Core\Helper\Registry;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Price extends Template
{
    /** @var Registry */
    protected $registryHelper;

    /** @var DataObject */
    protected $dataObjectHelper;

    /** @var FinalPrice */
    protected $finalPriceHelper;

    /** @var OldPrice */
    protected $oldPriceHelper;

    /** @var Information */
    protected $informationHelper;

    /** @var Json */
    protected $json;

    public function __construct(
        Template\Context $context,
        Registry $registryHelper,
        DataObject $dataObjectHelper,
        FinalPrice $finalPriceHelper,
        OldPrice $oldPriceHelper,
        Information $informationHelper,
        Json $json,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );

        $this->registryHelper = $registryHelper;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->finalPriceHelper = $finalPriceHelper;
        $this->oldPriceHelper = $oldPriceHelper;
        $this->informationHelper = $informationHelper;
        $this->json = $json;
    }

    public function getProductData(): string
    {
        $product = $this->getProduct();

        $informationPrice = $this->informationHelper->getPrice($product);

        return $this->json->encode([
            'finalPrice'  => [
                'label'       => $this->finalPriceHelper->getLabel(
                    $product,
                    ''
                ),
                'information' => $this->finalPriceHelper->getInformation($product)
            ],
            'oldPrice'    => [
                'label'       => $this->oldPriceHelper->getLabel(
                    $product,
                    ''
                ),
                'information' => $this->oldPriceHelper->getInformation($product)
            ],
            'information' => [
                'label'       => $this->informationHelper->getLabel(
                    $product,
                    ''
                ),
                'price'       => $informationPrice !== null ? $informationPrice->getValue() : null,
                'information' => $this->informationHelper->getInformation($product)
            ]
        ]);
    }

    public function getProduct(): Product
    {
        return $this->dataObjectHelper->getOrSetValueCallback(
            $this,
            'product',
            function () {
                return $this->registryHelper->registry('product');
            }
        );
    }
}
