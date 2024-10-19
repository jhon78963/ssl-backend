<?php

namespace App\Category\Services;

use App\Category\Models\Category;
use App\Shared\Services\ModelService;

class CategoryService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newCategory): void
    {
        $this->modelService->create(new Category(), $newCategory);
    }

    public function delete(Category $category): void
    {
        $this->modelService->delete($category);
    }

    public function update(Category $category, array $editCategory): void
    {
        $this->modelService->update($category, $editCategory);
    }

    public function validate(Category $category, string $modelName): Category
    {
        return $this->modelService->validate($category, $modelName);
    }
}
