<?php

namespace App\Room\Controllers;

use App\Image\Models\Image;
use App\Image\Resources\ImageResource;
use App\Image\Services\ImageService;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\FileUploadRequest;
use App\Shared\Services\FileService;
use App\Shared\Services\ModelRelationService;
use Illuminate\Http\JsonResponse;

class RoomImageController extends Controller
{
    private string $path_images = 'images/rooms';
    protected FileService $fileService;
    protected ImageService $imageService;
    protected ModelRelationService $modelRelationService;

    public function __construct(FileService $fileService, ImageService $imageService, ModelRelationService $modelRelationService)
    {
        $this->fileService = $fileService;
        $this->imageService = $imageService;
        $this->modelRelationService = $modelRelationService;
    }

    public function add(Room $room, Image $image): JsonResponse
    {
        $result = $this->modelRelationService->attach($room, 'images', $image->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Image added to the room.'], 201);
    }

    public function multipleAdd(FileUploadRequest $request, Room $room)
    {
        $uploadedImages = $this->fileService->uploadMultiple($request, $this->path_images);
        $savedImages = [];
        foreach ($uploadedImages as $roomImagePath) {
            $roomImageName = $this->imageService->getFileName($roomImagePath);
            $roomImage = $this->imageService->save($roomImageName, $roomImagePath);
            $result = $this->modelRelationService->attach($room, 'images', $roomImage->id);
            if ($result && isset($result['error'])) {
                return response()->json(['message' => $result['error']]);
            }
            $savedImages[] = $roomImage;
        }
        return response()->json([
            'message' => 'Images added to the room.',
            'images' => $savedImages,
        ], 201);
    }

    public function getAll(Room $room): JsonResponse
    {
        $images = $room->images()->orderBy('id', 'desc')->get();
        return response()->json( ImageResource::collection($images));
    }

    public function getLeft(Room $room): JsonResponse
    {
        $allImages = Image::where('is_deleted', false)->get();
        $associatedImages = $room->images()->pluck('id')->toArray();
        $leftImages = $allImages->whereNotIn('id', $associatedImages);
        return response()->json( ImageResource::collection($leftImages));
    }

    public function remove(Room $room, Image $image): JsonResponse
    {
        $result = $this->modelRelationService->detach($room, 'images', $image->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Image removed from the room']);
    }
}
