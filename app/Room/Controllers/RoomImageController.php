<?php

namespace App\Room\Controllers;

use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\ImageUploadRequest;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;

class RoomImageController extends Controller
{
    private string $path_images = 'public/images/rooms';
    protected $sharedService;

    public function __construct(SharedService $sharedService)
    {
        $this->sharedService = $sharedService;
    }

    public function uploadImageRooms(ImageUploadRequest $request, Room $room): JsonResponse
    {
        $roomPicturePath = $this->sharedService->uploadImage($request, $this->path_images);
        $roomPicture = basename($roomPicturePath);
        $roomPicture = $this->sharedService->saveImage($roomPicturePath, $roomPicture);
        $room->images()->attach($roomPicture);

        return response()->json(['message' => 'Room picture uploaded.'], 201);
    }
}
