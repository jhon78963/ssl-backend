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
        return [
            'id'=> $this->id,
            'imageName' => $this->name,
            'imagePath' => "https://zerogroups-storage.s3.us-east-1.amazonaws.com/$this->path",
        ];
    }
}
