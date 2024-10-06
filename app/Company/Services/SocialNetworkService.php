<?php

namespace App\Company\Services;

use App\Company\Models\SocialNetwork;
use Auth;

class SocialNetworkService
{
    public function add(array $newSocialNetwork): void
    {
        $socialNetwork = new SocialNetwork();
        $socialNetwork->name = $newSocialNetwork['name'];
        $socialNetwork->url = $newSocialNetwork['url'];
        $socialNetwork->icon = $newSocialNetwork['icon'];
        $socialNetwork->company_id = $newSocialNetwork['companyId'];
        $socialNetwork->creator_user_id = Auth::id();
        $socialNetwork->save();
    }

    public function update(SocialNetwork $socialNetwork, array $editSocialNetwork): void
    {
        $socialNetwork->name = $editSocialNetwork['name'] ?? $socialNetwork->nam;
        $socialNetwork->url = $editSocialNetwork['url'] ?? $socialNetwork->url;
        $socialNetwork->icon = $editSocialNetwork['icon'] ?? $socialNetwork->icon;
        $socialNetwork->last_modification_time = now()->format('Y-m-d H:i:s');
        $socialNetwork->last_modifier_user_id = Auth::id();
        $socialNetwork->save();
    }
}
