<?php

namespace App\Image\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseUrl = config('app.url') . '/storage';

        return [
            'id'=> $this->id,
            'imageName' => $this->name,
            'imagePath' => "$baseUrl/$this->path",
        ];
    }
}
