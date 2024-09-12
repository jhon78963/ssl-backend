<?php

namespace App\Shared\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'fileName' => $this->file_name,
            'filePath' => "$baseUrl/$this->file_path",
        ];
    }
}
