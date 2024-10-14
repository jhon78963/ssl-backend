<?php

namespace App\Rate\Services;

use App\Rate\Models\RateDay;
use App\Shared\Services\ModelService;

class RateDayService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newRateDay): void
    {
        $this->modelService->create(
            new RateDay(),
            $newRateDay,
        );
    }

    public function delete(RateDay $rateDay): void
    {
        $this->modelService->delete($rateDay);
    }

    public function update(RateDay $rateDay, array $editRateDay): void
    {
        $this->modelService->update(
            $rateDay,
            $editRateDay,
        );
    }

    public function validate(RateDay $rateDay, string $modelName): mixed
    {
        return $this->modelService->validate($rateDay, $modelName);
    }
}
