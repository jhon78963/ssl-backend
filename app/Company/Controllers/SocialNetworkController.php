<?php

namespace App\Company\Controllers;

use App\Company\Models\SocialNetwork;
use App\Company\Requests\SocialNetworkAddRequest;
use App\Company\Requests\SocialNetworkEditRequest;
use App\Company\Resources\SocialNetworkResource;
use App\Company\Services\SocialNetworkService;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use DB;

class SocialNetworkController
{
    protected $socialNetworkService;
    protected $sharedService;

    public function __construct(
        SocialNetworkService $socialNetworkService,
        SharedService $sharedService,
    )
    {
        $this->socialNetworkService = $socialNetworkService;
        $this->sharedService = $sharedService;
    }

    public function add(SocialNetworkAddRequest $request): JsonResponse
    {
        // DB::beginTransaction();
        // try {
            $this->socialNetworkService->add($request->validated());
            DB::commit();
            return response()->json(['message' => 'Social Netwrok added.']);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     throw new BadRequestException($e->getMessage());
        // }
    }

    public function edit(SocialNetworkEditRequest $request, SocialNetwork $socialNetwork): JsonResponse
    {
        DB::beginTransaction();
        try {
            $socialNetworkValidated = $this->sharedService->validateModel($socialNetwork, 'SocialNetwork');
            $this->socialNetworkService->update($socialNetworkValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Social Netwrok updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }

    public function get(SocialNetwork $socialNetwork): JsonResponse
    {
        $socialNetworkValidated = $this->sharedService->validateModel($socialNetwork, 'SocialNetwork');
        return response()->json(new SocialNetworkResource($socialNetworkValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Company', 'SocialNetwork', 'name');
        return response()->json(new GetAllCollection(
            SocialNetworkResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function remove(SocialNetwork $socialNetwork): JsonResponse
    {
        DB::beginTransaction();
        try {
            $socialNetworkValidated = $this->sharedService->validateModel($socialNetwork, 'SocialNetwork');
            $this->sharedService->deleteModel($socialNetworkValidated);
            DB::commit();
            return response()->json(['message' => 'Social Netwrok removed.']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }
}
