<?php

namespace App\Image\Controllers;

use App\Image\Resources\ImageResource;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    protected SharedService $sharedService;

    public function __construct(SharedService $sharedService)
    {
        $this->sharedService = $sharedService;
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
