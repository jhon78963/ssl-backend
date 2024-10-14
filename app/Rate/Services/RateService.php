<?php

namespace App\Rate\Services;

use App\Rate\Models\Rate;
use App\Shared\Services\ModelService;

class RateService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newRate): void
    {
        $this->modelService->create(
            new Rate(),
            $newRate,
        );
    }

    public function delete(Rate $rate): void
    {
        $this->modelService->delete($rate);
    }

    public function update(Rate $rate, array $editRate): void
    {
        $this->modelService->update(
            $rate,
            $editRate,
        );
    }

    public function validate(Rate $rate, string $modelName): Rate
    {
        return $this->modelService->validate($rate, $modelName);
    }
}
