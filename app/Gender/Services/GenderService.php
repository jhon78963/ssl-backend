<?php
namespace App\Gender\Services;

use App\Gender\Models\Gender;
use App\Shared\Services\ModelService;

class GenderService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function validate(Gender $gender, string $modelName): Gender
    {
        return $this->modelService->validate($gender, $modelName);
    }
}
