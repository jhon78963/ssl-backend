<?php
namespace App\Image\Services;

use App\Image\Models\Image;
use App\Shared\Services\SharedService;
use Auth;

class ImageService {
    protected $sharedService;

    public function __construct(SharedService $sharedService)
    {
        $this->sharedService = $sharedService;
    }

    public function getFileName(string $filePath): string
    {
        return basename($filePath);
    }

    public function save(string $fileName, string $filePath): Image
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
        $this->sharedService->deleteModel($image);
        $this->detachFromAllPivotTables($image);
    }

    private function detachFromAllPivotTables(Image $image): void
    {
        $image->rooms()->detach();
    }
}
