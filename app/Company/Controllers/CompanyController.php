<?php

namespace App\Company\Controllers;

use App\Company\Models\Company;
use App\Company\Requests\CompanyUpdateRequest;
use App\Company\Resources\CompanyResource;
use App\Company\Services\CompanyService;
use App\Shared\Controllers\Controller;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class CompanyController extends Controller
{
    protected CompanyService $companyService;
    protected SharedService $sharedService;

    public function __construct(CompanyService $companyService, SharedService $sharedService)
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
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
