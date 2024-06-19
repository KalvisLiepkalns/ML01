<?php

namespace Magebit\ProductAttributeFilter\Helper;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute\Interceptor;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductAttributeFilter extends AbstractHelper
{

    private CollectionFactory $attributeCollection;
    private ProductRepositoryInterface $productRepository;
    private Http $request;

    // Base attributes that are priority
    private array $baseAttributes = ["dimension", "color", "material"];

    public function __construct(Context $context, CollectionFactory $attributeCollection, Http $request, ProductRepositoryInterface $productRepository)
    {
        parent::__construct($context);
        $this->attributeCollection = $attributeCollection;
        $this->request = $request;
        $this->productRepository = $productRepository;

    }

    /**
     * Sets the attributes that are the priority in the view.
     * @param string[] $attributes
     * @return void \
     * ``$attributes`` - example: ["color", "material", ...]
     */
    public function setBaseAttributes(array $attributes): void {
        $this->baseAttributes = $attributes;
    }

    /**
     * Gets a valid attribute that is not included in ``$filteredAttributes``
     * @param Product $product - the current product
     * @param array $filteredAttributes
     *
     * @return null|Interceptor
     */
    private function getValidAttribute(Product $product, array $filteredAttributes): null|Interceptor
    {

        $otherAttributes = $this->attributeCollection->create();
        $otherAttributes->addFilter('is_user_defined', 1);

        foreach ($otherAttributes as $attribute) {
            $value = $attribute->getFrontend()->getValue($product);
            $label = $attribute->getStorelabel();

            if ($value && $label && !isset($filteredAttributes[$label])) return $attribute;
        }

        return null;
    }

    /**
     * Gets filtered attributes for the current product
     * @param Product $product - the current product.
     * @return Interceptor[]
     * Return example: ["Color" => Interceptor, "Material" => Interceptor, ...]
     */
    public function getFilteredAttributes(Product $product): array
    {
        $filteredAttributes = [];
        $attributes = $product->getAttributes();

        foreach ($this->baseAttributes as $attributeIdentifier) {
            $value = $attributes[$attributeIdentifier]->getFrontend()->getValue($product);
            $label = $attributes[$attributeIdentifier]->getStorelabel();

            if ($value && isset($attributes[$attributeIdentifier])) $filteredAttributes[$label] = $attributes[$attributeIdentifier];
            else {
                $attribute = $this->getValidAttribute($product, $filteredAttributes);
                if ($attribute) $filteredAttributes[$attribute->getStorelabel()] = $attribute;
            }
        }

        return $filteredAttributes;
    }

    /**
     * Gets the current id by taking it from the path info.
     * @return int
     */
    private function getCurrentProductId(): int
    {
        // Might not be the best, but it's one idea that came to my mind
        return intval($this->request->getParam("id"));
    }

    /**
     * Gets the current product.
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function getProduct(): ProductInterface
    {
        return $this->productRepository->getById($this->getCurrentProductId());
    }

    /**
     * Gets the description of ``$product`` till it reaches the first dot.
     * @param Product $product
     * @return string
     */
    public function getDescription(Product $product): string
    {
        /*
         * Wasn't required, but by guessing the design, the short description starts from first tag and till the first "."
         */
        $description = explode("\n", $product->getDescription())[0];
        $description = strip_tags($description);
        return explode(".",$description)[0] . ".";
    }

}
