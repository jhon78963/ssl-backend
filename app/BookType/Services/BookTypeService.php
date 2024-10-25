<?php

namespace App\BookType\Services;

use App\BookType\Models\BookType;
use App\Shared\Services\ModelService;

class BookTypeService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newBookType): void
    {
        $this->modelService->create(new BookType(), $newBookType);
    }

    public function delete(BookType $bookType): void
    {
        $this->modelService->delete($bookType);
    }

    public function update(BookType $bookType, array $editBookType): void
    {
        $this->modelService->update($bookType, $editBookType);
    }

    public function validate(BookType $bookType, string $modelName): BookType
    {
        return $this->modelService->validate($bookType, $modelName);
    }
}
