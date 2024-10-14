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

    public function create(string $fileName, string $filePath): Image
    {
        $image = new Image();
        $image->name = $fileName;
        $image->path = $filePath;
        $image->creator_user_id = Auth::id();
        $image->save();

        return $image;
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

    public function validate(Image $image, string $modelName): mixed
    {
        return $this->modelService->validate($image, $modelName);
    }

    private function detachFromAllPivotTables(Image $image): void
    {
        $image->rooms()->detach();
    }
}
