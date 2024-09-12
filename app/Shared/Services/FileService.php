<?php

namespace App\Shared\Services;

use App\Shared\Requests\FileUploadRequest;
use Storage;

class FileService
{
    public function upload(FileUploadRequest $request, String $filePath): ?string
    {
        return ($request->hasFile("file"))
            ? $request->file("file")->store($filePath, 'public')
            : NULL;
    }

    public function get(string $filePath): ?string
    {
        return Storage::disk('public')->exists($filePath)
            ? Storage::disk('public')->path($filePath)
            : NULL;
    }

    public function delete(string $filePath): ?string
    {
        return Storage::disk('public')->exists($filePath)
            ? Storage::disk('public')->delete($filePath)
            : NULL;
    }
}
