<?php

namespace App\Room\Controllers;

use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Models\Picture;
use App\Shared\Requests\FileUploadRequest;
use App\Shared\Services\FileService;
use App\Shared\Services\ImageService;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;

class RoomImageController extends Controller
{
    private string $path_images = 'images/rooms';
    protected $fileService;
    protected $imageService;

    public function __construct(FileService $fileService, ImageService $imageService)
    {
        $this->fileService = $fileService;
        $this->imageService = $imageService;
    }

    public function add(FileUploadRequest $request, Room $room): JsonResponse
    {
        $roomPicturePath = $this->fileService->upload($request, $this->path_images);
        $roomPicture = basename($roomPicturePath);
        $roomPicture = $this->imageService->save($roomPicture, $roomPicturePath);
        $room->images()->attach($roomPicture);

        return response()->json(['message' => 'Room picture uploaded.'], 201);
    }

    public function remove(Room $room, Picture $picture): JsonResponse
    {
        $this->fileService->delete($picture->file_path);
        $this->imageService->delete($picture);
        $room->images()->detach($picture->id);

        return response()->json(['message' => 'Room picture removed.'], 201);
    }
}
