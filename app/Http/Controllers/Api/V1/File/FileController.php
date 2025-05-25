<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileRequest;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function streamFile(FileRequest $request)
    {
        $request->validated();

        if (!$request->path || !Storage::disk('local')->exists($request->path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('local')->response($request->path);
    }
}
