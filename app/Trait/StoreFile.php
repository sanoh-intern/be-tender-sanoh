<?php

namespace App\Trait;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait StoreFile
{
    /**
     * save and store file to storage
     *
     * @param  mixed  $file
     * @return bool|string
     */
    public function saveFile($file, string $name, string $folder, string $disk = 'local')
    {
        $fileName = Carbon::now()->format('Ymd_his').'_'.uniqid($name).'_'.str_replace(' ', '_', $file->getClientOriginalName());

        // Save file
        $filePath = Storage::disk($disk)->putFileAs("file/$folder", $file, $fileName);

        return $filePath;
    }

    public function deleteFile(string $filePath, string $disk = 'local')
    {
        if (! Storage::disk($disk)->exists($filePath)) {
            return false;
        } else {
            Storage::disk($disk)->delete($filePath);

            return true;
        }
    }
}
