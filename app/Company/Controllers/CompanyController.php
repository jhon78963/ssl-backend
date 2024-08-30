<?php

namespace App\Company\Controllers;

use App\Company\Models\Company;
use App\Company\Models\SocialNetwork;
use App\Company\Requests\CompanyUpdateRequest;
use App\Company\Requests\SocialNetworkAddRequest;
use App\Company\Requests\SocialNetworkEditRequest;
use App\Company\Resources\CompanyResource;
use App\Company\Resources\SocialNetworkResource;
use App\Company\Services\CompanyService;
use App\Company\Services\SocialNetworkService;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use DB;

class CompanyController
{
    protected $companyService;
    protected $socialNetworkService;
    protected $sharedService;

    public function __construct(
        CompanyService $companyService,
        SocialNetworkService $socialNetworkService,
        SharedService $sharedService,
    )
    {
        $this->companyService = $companyService;
        $this->socialNetworkService = $socialNetworkService;
        $this->sharedService = $sharedService;
    }

    public function get(Company $company): JsonResponse
    {
        $companyValidated = $this->sharedService->validateModel($company, 'Company');
        return response()->json(new CompanyResource($companyValidated));
    }

    public function update(CompanyUpdateRequest $request, Company $company): JsonResponse
    {
        DB::beginTransaction();
        try {
            $companyValidated = $this->sharedService->validateModel($company, 'Company');
            $this->companyService->updateCompany(
                $companyValidated,
                $request->validated()
            );
            DB::commit();
            return response()->json(['message' => 'Company updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }

    public function addSocialNetwork(SocialNetworkAddRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->socialNetworkService->add($request->validated());
            DB::commit();
            return response()->json(['message' => 'Social Netwrok added.']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }

    public function editSocialNetwork(SocialNetworkEditRequest $request, SocialNetwork $socialNetwork): JsonResponse
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

    public function getSocialNetwork(SocialNetwork $socialNetwork): JsonResponse
    {
        $socialNetworkValidated = $this->sharedService->validateModel($socialNetwork, 'SocialNetwork');
        return response()->json(new SocialNetworkResource($socialNetworkValidated));
    }

    public function getAllSocialNetwork(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Company', 'SocialNetwork', 'name');
        return response()->json(new GetAllCollection(
            SocialNetworkResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function removeSocialNetwork(SocialNetwork $socialNetwork): JsonResponse
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
