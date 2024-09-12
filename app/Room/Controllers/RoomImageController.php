<?php

namespace App\Room\Controllers;

use App\Room\Models\Room;
use App\Room\Services\RoomRelationService;
use App\Shared\Controllers\Controller;
use App\Shared\Models\Picture;
use App\Shared\Requests\FileUploadRequest;
use App\Shared\Services\FileService;
use App\Shared\Services\ImageService;
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
        $roomPicturePath = $this->fileService->upload($request, $this->path_images);
        $roomPicture = basename($roomPicturePath);
        $roomPicture = $this->imageService->save($roomPicture, $roomPicturePath);
        $result = $this->roomRelationService->attach($room, 'images', $roomPicture->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function remove(Room $room, Picture $picture): JsonResponse
    {
        $this->fileService->delete($picture->file_path);
        $this->imageService->delete($picture);
        $result = $this->roomRelationService->detach($room, 'images', $picture->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
