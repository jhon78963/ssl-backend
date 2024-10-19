<?php

namespace App\ProductType\Services;
use App\ProductType\Models\ProductType;
use App\Shared\Services\ModelService;

class ProductTypeService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newProductType): void
    {
        $this->modelService->create(new ProductType(), $newProductType);
    }

    public function delete(ProductType $productType): void
    {
        $this->modelService->delete($productType);
    }

    public function update(ProductType $productType, array $editProductType): void
    {
        $this->modelService->update($productType, $editProductType);
    }

    public function validate(ProductType $productType, string $modelName): ProductType
    {
        return $this->modelService->validate($productType, $modelName);
    }
}
