<?php

namespace App\Company\Services;

use App\Company\Models\Company;
use App\Shared\Services\ModelService;

class CompanyService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function update(Company $company, array $editCompany): void
    {
        $this->modelService->update(
            $company,
            $editCompany,
        );
    }

    public function validate(Company $company, string $modelName): Company
    {
        return $this->modelService->validate($company, $modelName);
    }
}
