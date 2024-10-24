<?php

namespace App\Shared\Services;

use App\Shared\Requests\FileMultipleUploadRequest;
use Storage;

class FileService
{
    public function upload($request, String $filePath): ?string
    {
        return ($request->hasFile("file"))
            ? $request->file("file")->store($filePath)
            : NULL;
    }

    public function uploadMultiple(FileMultipleUploadRequest $request, String $filePath): array
    {
        $uploadedPaths = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $uploadedPaths[] = $file->store($filePath);
            }
        }
        return $uploadedPaths;
    }

    public function get(string $filePath): ?string
    {
        return Storage::disk('s3')->exists($filePath)
            ? Storage::disk('s3')->path($filePath)
            : NULL;
    }

    public function delete(string $filePath): ?string
    {
        return Storage::disk('s3')->exists($filePath)
            ? Storage::disk('s3')->delete($filePath)
            : NULL;
    }
}
