<?php

namespace App\Company\Controllers;

use App\Company\Models\SocialNetwork;
use App\Company\Requests\SocialNetworkAddRequest;
use App\Company\Requests\SocialNetworkEditRequest;
use App\Company\Resources\SocialNetworkResource;
use App\Company\Services\SocialNetworkService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class SocialNetworkController extends Controller
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
        DB::beginTransaction();
        try {
            $newNetworkValidated = $this->sharedService->convertCamelToSnake($request->validated());
            $this->socialNetworkService->add($newNetworkValidated);
            DB::commit();
            return response()->json(['message' => 'Social Netwrok added.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function edit(SocialNetworkEditRequest $request, SocialNetwork $socialNetwork): JsonResponse
    {
        DB::beginTransaction();
        try {
            $socialNetworkValidated = $this->socialNetworkService->validate($socialNetwork, 'SocialNetwork');
            $editCompany = $this->sharedService->convertCamelToSnake($request->validated());
            $this->socialNetworkService->update($socialNetworkValidated, $editCompany);
            DB::commit();
            return response()->json(['message' => 'Social Netwrok updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(SocialNetwork $socialNetwork): JsonResponse
    {
        $socialNetworkValidated = $this->socialNetworkService->validate($socialNetwork, 'SocialNetwork');
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
            $socialNetworkValidated = $this->socialNetworkService->validate($socialNetwork, 'SocialNetwork');
            $this->socialNetworkService->delete($socialNetworkValidated);
            DB::commit();
            return response()->json(['message' => 'Social Netwrok removed.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
