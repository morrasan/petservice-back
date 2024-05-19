<?php

namespace App\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoService {

    /**
     * @param UploadedFile $file
     *
     * @return bool|string
     */
    public function storePhoto (UploadedFile $file): bool|string {

        return $file->store('photos', 'public');
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function deletePhoto (string $path): void {

        Storage::disk('public')->delete($path);
    }
}
