<?php

namespace App\Rate\Services;

use App\Rate\Models\RateHour;
use App\Shared\Services\ModelService;

class RateHourService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newrateHour): void
    {
        $this->modelService->create(
            new RateHour(),
            $newrateHour,
        );
    }

    public function delete(RateHour $rateHour): void
    {
        $this->modelService->delete($rateHour);
    }

    public function update(RateHour $rateHour, array $editrateHour): void
    {
        $this->modelService->update(
            $rateHour,
            $editrateHour,
        );
    }

    public function validate(RateHour $rateHour, string $modelName): mixed
    {
        return $this->modelService->validate($rateHour, $modelName);
    }
}
