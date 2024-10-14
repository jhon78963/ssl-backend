<?php
namespace App\Image\Services;

use App\Image\Models\Image;
use App\Shared\Services\ModelService;
use Auth;

class ImageService {
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newImage): Image
    {
        return $this->modelService->create(new Image(), $newImage);
    }

    public function delete(Image $image): void
    {
        $this->modelService->delete($image);
        $this->detachFromAllPivotTables($image);
    }

    public function getFileName(string $filePath): string
    {
        return basename($filePath);
    }

    public function validate(Image $image, string $modelName): Image
    {
        return $this->modelService->validate($image, $modelName);
    }

    private function detachFromAllPivotTables(Image $image): void
    {
        $image->rooms()->detach();
    }
}
