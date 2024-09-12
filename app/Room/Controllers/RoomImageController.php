<?php

namespace App\Room\Controllers;

use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Models\Picture;
use App\Shared\Requests\ImageUploadRequest;
use App\Shared\Services\ImageService;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;

class RoomImageController extends Controller
{
    private string $path_images = 'images/rooms';
    protected $sharedService;
    protected $imageService;

    public function __construct(SharedService $sharedService, ImageService $imageService)
    {
        $this->sharedService = $sharedService;
        $this->imageService = $imageService;
    }

    public function add(ImageUploadRequest $request, Room $room): JsonResponse
    {
        $roomPicturePath = $this->sharedService->uploadImage($request, $this->path_images);
        $roomPicture = basename($roomPicturePath);
        $roomPicture = $this->imageService->save($roomPicture, $roomPicturePath);
        $room->images()->attach($roomPicture);

        return response()->json(['message' => 'Room picture uploaded.'], 201);
    }

    public function remove(Room $room, Picture $picture): JsonResponse
    {
        $test = $this->sharedService->deleteImage($picture->file_path);
        $this->imageService->delete($picture);
        $room->images()->detach($picture->id);

        return response()->json(['message' => 'Room picture removed.'], 201);
    }
}
