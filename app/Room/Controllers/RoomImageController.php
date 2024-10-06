<?php

namespace App\Room\Controllers;

use App\Image\Models\Image;
use App\Image\Services\ImageService;
use App\Room\Models\Room;
use App\Room\Services\RoomRelationService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\FileUploadRequest;
use App\Shared\Services\FileService;
use Illuminate\Http\JsonResponse;

class RoomImageController extends Controller
{
    private string $path_images = 'images/rooms';
    protected FileService $fileService;
    protected ImageService $imageService;
    protected RoomRelationService $roomRelationService;

    public function __construct(FileService $fileService, ImageService $imageService, RoomRelationService $roomRelationService)
    {
        $this->fileService = $fileService;
        $this->imageService = $imageService;
        $this->roomRelationService = $roomRelationService;
    }

    public function add(FileUploadRequest $request, Room $room): JsonResponse
    {
        $roomImagePath = $this->fileService->upload($request, $this->path_images);
        $roomImage = basename($roomImagePath);
        $roomImage = $this->imageService->save($roomImage, $roomImagePath);
        $result = $this->roomRelationService->attach($room, 'images', $roomImage->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function remove(Room $room, Image $image): JsonResponse
    {
        $this->fileService->delete($image->path);
        $this->imageService->delete($image);
        $result = $this->roomRelationService->detach($room, 'images', $image->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
