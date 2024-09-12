<?php
namespace App\Shared\Services;

use App\Room\Models\Room;
use App\Shared\Models\Picture;
use Auth;

class ImageService {
    protected $sharedService;

    public function __construct(SharedService $sharedService)
    {
        $this->sharedService = $sharedService;
    }
    public function save(string $fileName, string $filePath): Picture
    {
        $picture = new Picture();
        $picture->file_name = $fileName;
        $picture->file_path = $filePath;
        $picture->creator_user_id = Auth::id();
        $picture->save();

        return $picture;
    }

    public function delete(Picture $picture): void
    {
        $this->sharedService->deleteModel($picture);
    }
}
