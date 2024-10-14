<?php

namespace App\Room\Controllers;

use App\Image\Models\Image;
use App\Image\Resources\ImageResource;
use App\Image\Services\ImageService;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\FileMultipleUploadRequest;
use App\Shared\Services\FileService;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class RoomImageController extends Controller
{
    private string $path_images = 'images/rooms';
    protected FileService $fileService;
    protected ImageService $imageService;
    protected ModelService $modelService;

    public function __construct(FileService $fileService, ImageService $imageService, ModelService $modelService)
    {
        $this->fileService = $fileService;
        $this->imageService = $imageService;
        $this->modelService = $modelService;
    }

    public function add(Room $room, Image $image): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach($room, 'images', $image->id);
            DB::commit();
            return response()->json(['message' => 'Image added to the room.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function multipleAdd(FileMultipleUploadRequest $request, Room $room)
    {
        DB::beginTransaction();
        try {
            $uploadedImages = $this->fileService->uploadMultiple($request, $this->path_images);
            foreach ($uploadedImages as $roomImagePath) {
                $roomImageName = $this->imageService->getFileName($roomImagePath);
                $roomImage = $this->imageService->create([
                    'name' => $roomImageName,
                    'path' => $roomImagePath
                ]);
                $this->modelService->attach($room, 'images', $roomImage->id);
            }
            DB::commit();
            return response()->json(['message' => 'Images added to the room.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
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
        DB::beginTransaction();
        try {
            $this->modelService->detach($room, 'images', $image->id);
            DB::commit();
            return response()->json(['message' => 'Image removed from the room']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
