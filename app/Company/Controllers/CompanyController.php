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
    protected $sharedService;

    public function __construct(
        CompanyService $companyService,
        SharedService $sharedService,
    )
    {
        $this->companyService = $companyService;
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
}
