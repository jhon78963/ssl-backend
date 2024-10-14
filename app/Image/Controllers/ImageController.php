<?php

namespace App\Image\Controllers;

use App\Image\Models\Image;
use App\Image\Resources\ImageResource;
use App\Image\Services\ImageService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\FileService;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    protected FileService $fileService;
    protected ImageService $imageService;
    protected SharedService $sharedService;

    public function __construct(
        FileService $fileService,
        ImageService $imageService,
        SharedService $sharedService,
    ) {
        $this->fileService = $fileService;
        $this->imageService = $imageService;
        $this->sharedService = $sharedService;
    }

    public function delete(Image $image): JsonResponse
    {
        $this->fileService->delete($image->path);
        $this->imageService->delete($image);
        return response()->json([
            'message' => 'Image removed from system'
        ]);
    }

    public function get(Image $image): JsonResponse
    {
        $imageValidated = $this->imageService->validate($image, 'Image');
        return response()->json(new ImageResource($imageValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Image', 'Image', 'name');
        return response()->json(new GetAllCollection(
            ImageResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }
}
