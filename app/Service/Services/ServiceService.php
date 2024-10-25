<?php

namespace App\Service\Services;

use App\Service\Models\Service;
use App\Shared\Services\ModelService;

class ServiceService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newService): void
    {
        $this->modelService->create(new Service(), $newService);
    }

    public function delete(Service $service): void
    {
        $this->modelService->delete($service);
    }

    public function update(Service $service, array $editService): void
    {
        $this->modelService->update($service, $editService);
    }

    public function validate(Service $service, string $modelName): Service
    {
        return $this->modelService->validate($service, $modelName);
    }
}
