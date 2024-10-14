<?php

namespace App\Company\Services;

use App\Company\Models\SocialNetwork;
use App\Shared\Services\ModelService;

class SocialNetworkService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(array $newSocialNetwork): void
    {
        $this->modelService->create(new SocialNetwork(), $newSocialNetwork);
    }

    public function delete(SocialNetwork $socialNetwork): void
    {
        $this->modelService->delete($socialNetwork);
    }

    public function update(SocialNetwork $socialNetwork, array $editSocialNetwork): void
    {
        $this->modelService->update($socialNetwork, $editSocialNetwork);
    }

    public function validate(SocialNetwork $socialNetwork, string $modelName): SocialNetwork
    {
        return $this->modelService->validate($socialNetwork, $modelName);
    }
}
